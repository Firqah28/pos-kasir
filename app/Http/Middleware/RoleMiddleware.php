<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // If user role matches any of the required roles, allow proceed
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect back to dashboard if access denied
        return redirect()->route('dashboard')->withErrors(['error' => 'Akses ditolak: Anda tidak memiliki izin untuk halaman ini.']);
    }
}
