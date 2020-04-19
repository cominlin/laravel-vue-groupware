<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response_data($type, $message = '')
    {
        switch ($type) {
            case 'error_input':
                return response([
                    'status' => 'error',
                    'message' => '入力した情報が正しくありません'
                ], 422);
            case 'error_remove':
                return response([
                    'status' => 'error',
                    'message' => trans('auth.cant_remove', [], auth()->user()->language)
                ], 400);
            case 'no_auth':
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'message' => trans('auth.no_auth', [], auth()->user()->language)
                ], 401);
            case 'has_message':
                return response([
                    'status' => 'error',
                    'message' => $message
                ], 422);
            case 'error_data':
                return response([
                    'status' => 'error',
                    'message' => trans('auth.wrong_data', [], auth()->user()->language)
                ], 400);
            case 'need_authority':
                return response([
                    'status' => 'error',
                    'message' => trans('auth.wrong_data', [], auth()->user()->language)
                ], 403);
            case 'not_found':
                return response([
                    'status' => 'error',
                    'message' => trans('auth.id_no_data', [], auth()->user()->language)
                ], 400);
        }
    }
}
