<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\TokenStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user with WhatsApp and password
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->getCredentials();

        // Attempt to find user by whatsapp
        $user = User::findByWhatsapp($credentials['whatsapp']);

        if (!$user) {
            return $this->error('whatsApp atau password salah!');
        }

        // Check password
        if (!Hash::check($credentials['password'], $user->password)) {
            return $this->error('whatsApp atau password salah!');
        }

        // Check if user is active
        if (!$user->is_active) {
             return $this->error('Akun ini tidak aktif. Silakan hubungi administrator.');
        }

        // Create token
        $token = $user->createToken('pos-token')->plainTextToken;

        // Update last login
        $user->updateLastLogin();

        // Prepare user data for storage
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'whatsapp' => $user->whatsapp,
            'email' => $user->email,
            'role' => $user->role,
            'alamat' => $user->alamat,
        ];

        // Save token to storage
        $tokenSaved = $this->tokenStorage->saveToken($user->id, $token, $userData);

        return response()->json([
            'success' => true,
            'metadata' => [
                'user' => [
                    'id' => $user->id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'whatsapp' => $user->whatsapp,
                    'email' => $user->email,
                    'role' => $user->role,
                    'alamat' => $user->alamat,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            'message' => 'Login berhasil.',
        ]);
    }

    /**
     * Get current authenticated user
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'metadata' => [
                'user' => [
                    'id' => $request->user()->id,
                    'nama_lengkap' => $request->user()->nama_lengkap,
                    'whatsapp' => $request->user()->whatsapp,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                    'alamat' => $request->user()->alamat,
                    'last_login_at' => $request->user()->last_login_at,
                ]
            ],
            'message' => 'Data user berhasil diambil.'
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }

    /**
     * Check if user is authenticated
     */
    public function checkAuth(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'authenticated' => Auth::check(),
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'role' => $request->user()->role,
                ] : null
            ]
        ]);
    }
}