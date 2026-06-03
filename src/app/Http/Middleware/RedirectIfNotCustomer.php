<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            if ($user->hasRole('super_admin')) {
                return redirect('/super-admin');
            }
            if ($user->hasRole('pegawai')) {
                return redirect('/pegawai');
            }
            if (!$user->hasRole('user')) {
                auth()->logout();
                return redirect()->route('login')->withErrors(['email' => 'Halaman ini khusus untuk pelanggan.']);
            }
        }

        return $next($request);
    }
}
