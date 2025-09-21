<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has admin, manager, or bendahara role
        if (!$user->hasAnyRole(['Admin', 'Manager', 'Bendahara'])) {
            // If user is pegawai, redirect to beranda
            if ($user->hasRole('Pegawai')) {
                return redirect()->route('pegawai.beranda');
            }

            // For other roles or no role, redirect to home with error message
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
