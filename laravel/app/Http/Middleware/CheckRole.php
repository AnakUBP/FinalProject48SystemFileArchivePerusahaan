<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
