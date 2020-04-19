<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserResignRequest;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function get_user_list()
    {
        return response([
            'status' => 'success',
            'users' => User::with(Config::get('constants.user_with'))
                ->where('type', '<>', 0)
                ->get(),
            'retired_users' => User::with(Config::get('constants.user_with'))
                ->where('type', '=', 0)
                ->get(),
        ]);
    }

    public function add_user(UserRequest $request)
    {
        if (!empty(User::where('email', $request->user['email'])->first())) {
            return self::response_data('has_message', __('auth.already_exist'));
        }
        $password = Str::random(8);

        $url = url('/login');

        Mail::send('emails.add-user', ['password' => $password, 'email' => $request->user['email'], 'url' => $url], function ($m) use ($request) {
            $m->to($request->user['email'])->subject('[Groupware] '.__('email.add_title'));
        });

        $user = new User();
        $user->fill($request->user);
        $user->password = bcrypt($password);

        $user->save();

        foreach ($request->groups as $g) {
            $user->groups()->attach($g);
        }

        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get(),
            'users' => User::with(Config::get('constants.user_with'))
                ->where('type', '<>', 0)
                ->get(),
            'retired_users' => User::with(Config::get('constants.user_with'))
                ->where('type', '=', 0)
                ->get(),
        ]);
    }

    public function edit_user(UserRequest $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->user);
        $user->save();

        $user->groups()->detach();
        foreach ($request->groups as $g) {
            $user->groups()->attach($g);
        }

        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get(),
            'users' => User::with(Config::get('constants.user_with'))
                ->where('type', '<>', 0)
                ->get(),
            'retired_users' => User::with(Config::get('constants.user_with'))
                ->where('type', '=', 0)
                ->get(),
        ]);
    }

    public function resign_user(UserResignRequest $request, $id)
    {
        $user = User::find($request->id);
        if ($request->type == 0) {
            $user->groups()->detach();
        }
        $user->type = $request->type;
        $user->save();

        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get(),
            'users' => User::with(Config::get('constants.user_with'))
                ->where('type', '<>', 0)
                ->get(),
            'retired_users' => User::with(Config::get('constants.user_with'))
                ->where('type', '=', 0)
                ->get(),
        ]);
    }

    public function reset_password($id)
    {
        if (!empty($id) && $user = User::find($id)) {

            $password = Str::random(8);

            Mail::send('emails.password-reset', ['password' => $password], function ($m) use ($user) {
                $m->to($user->email)->subject('[Groupware] '.__('email.reset_title'));
            });

            $user->password = bcrypt($password);
            $user->save();

            return response([
                'status' => 'success',
            ]);
        }
        return self::response_data('error_data');
    }
}
