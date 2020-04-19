<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckUserAuthority
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $authority)
    {
        if (auth()->user()->type < $authority) {
            return response([
                'status' => 'error',
                'message' => __('auth.no_admin_auth')
            ], 403);
        }
        return $next($request);
    }
}
