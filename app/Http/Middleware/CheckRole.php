<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect('/');
        }

        $user = $request->user();

        // Izinkan admin mengakses semua route
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, cek apakah rolenya sesuai
        if ($user->role !== $role) {
            abort(403, 'Unauthorized. Role tidak memenuhi.');
        }

        return $next($request);
    }
}