<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use DB;

class AuthController extends Controller
{
    public function login()
    {
        $user = User::whereEmail(request('username'))->first();

        if (!$user) {
            return self::response_data('error_input');
        }

        if (!Hash::check(request('password'), $user->password)) {
            return self::response_data('error_input');
        }

        if ($user->state == 1) {
            return self::response_data('has_message', 'このアカウントは使えません。');
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_Client_ID2'),
            'client_secret' => env('PASSPORT_Client_Secret2'),
            'username' => request('username'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return self::response_data('error_input');
        }

        $data = json_decode($response->getContent());

        return response([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 'success',
        ]);
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }

    public function get_user()
    {
        return response([
            'user' => auth()->user(),
            'status' => 'success',
        ]);
    }

    public function change_lang(Request $request)
    {
        if (!empty($request->lang) && in_array($request->lang, User::LANGUAGE_LIST)) {
            $user = Auth::user();
            $user->language = $request->lang;
            $user->save();
        }
        return response([
            'status' => 'success',
        ]);
    }
}
