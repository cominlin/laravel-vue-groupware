<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function get_user_list()
    {
        return response([
            'status' => 'success',
            'user' => User::all(),
        ]);
    }
}
