<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\GroupMemberEditRequest;
use App\Http\Requests\GroupRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GroupController extends Controller
{
    public function get_group_list()
    {
        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get()
        ]);
    }

    public function add_group(GroupRequest $request)
    {
        $group = new Group();
        $group->name = $request->name;
        $group->save();

        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get()
        ]);
    }

    public function edit_group(GroupRequest $request, $id)
    {
        $group = Group::find($id);
        $group->name = $request->name;
        $group->save();
        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get()
        ]);

    }

    public function remove_group($id)
    {
        Group::destroy($id);
        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get()
        ]);

    }

    public function edit_group_member(GroupMemberEditRequest $request, $id)
    {
        $group = Group::find($id);
        $group->members()->detach();
        if (!empty($request->members)) {
            $group->members()->attach($request->members);
        }
        return response([
            'status' => 'success',
            'groups' => Group::withCount('members')->get(),
            'users' => User::with(Config::get('constants.user_with'))
                ->where('type', '<>', 0)
                ->get(),
            'resigned_users' => User::with(Config::get('constants.user_with'))
                ->where('type', '=', 0)
                ->get(),
        ]);
    }
}
