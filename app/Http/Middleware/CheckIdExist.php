<?php

namespace App\Http\Middleware;

use Closure;

class CheckIdExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $class_name)
    {
        $obj = $class_name::find($request->route('id'));
        if (!$obj) {
            return response([
                'status' => 'error',
                'message' => __('auth.id_no_data')
            ], 400);
        }
        return $next($request);
    }
}
