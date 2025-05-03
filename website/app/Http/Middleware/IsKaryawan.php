<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsKaryawan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'karyawan') {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized (Karyawan only)'], 403);
    }
}
