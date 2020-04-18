<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function members()
    {
        return $this->belongsToMany('App\User', 'user_group', 'group_id', 'user_id');
    }

    public function memberCount()
    {
        return $this->belongsToMany('App\User', 'user_group', 'group_id', 'user_id')->count();
    }
}
