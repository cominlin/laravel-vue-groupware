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
                    'message' => '削除できませんでした'
                ], 400);
            case 'no_auth':
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'message' => '無効な権限'
                ], 401);
            case 'has_message':
                return response([
                    'status' => 'error',
                    'message' => $message
                ], 422);
            case 'error_data':
                return response([
                    'status' => 'error',
                    'message' => '不正なデータ'
                ], 400);
            case 'need_authority':
                return response([
                    'status' => 'error',
                    'message' => '管理権限が必要です。'
                ], 403);
            case 'not_found':
                return response([
                    'status' => 'error',
                    'message' => 'そのデータがありません。'
                ], 400);
        }
    }
}
