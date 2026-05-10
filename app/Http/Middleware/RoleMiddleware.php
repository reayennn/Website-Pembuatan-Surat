<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            if ($request->user()) {
                if ($request->user()->role === 'admin') {
                    return redirect('/admin/dashboard');
                } elseif ($request->user()->role === 'kepala_desa') {
                    return redirect('/kepala-desa/dashboard');
                }
                return redirect('/dashboard');
            }
            return redirect('/login');
        }

        return $next($request);
    }
}
