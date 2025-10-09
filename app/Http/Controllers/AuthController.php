<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ],[
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required'  => 'Password wajib diisi'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Regenerate session untuk mencegah Session Fixation Attack
            $request->session()->regenerate();

            if(Auth::user()->role === 'admin') {
                // Login berhasil, arahkan ke dashboard
                return redirect()->intended('/cms/dashboard')->with('success', 'Selamat datang! Anda berhasil login.');
            }else {
                // Login berhasil, arahkan ke dashboard
                return redirect()->intended('/kasir')->with('success', 'Selamat datang! Anda berhasil login.');
            }
        }

        return back()->with('error', 'Email atau Password salah.')->onlyInput('email');
    }
}
