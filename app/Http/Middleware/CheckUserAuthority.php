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
                'message' => '管理権限が必要です。'
            ], 403);
        }
        return $next($request);
    }
}
