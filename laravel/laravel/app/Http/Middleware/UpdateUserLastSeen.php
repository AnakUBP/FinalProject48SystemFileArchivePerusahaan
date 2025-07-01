<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UpdateUserLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = 'last-seen-' . $user->id;

            // Update timestamp hanya jika cache sudah expired (setiap 1 menit)
            if (!Cache::has($cacheKey)) {
                $user->last_seen = Carbon::now();
                $user->save();

                Cache::put($cacheKey, true, now()->addMinutes(1));
            }
        }
        return $next($request);
    }
}
