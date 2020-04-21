<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\ScheduleCategory', 'schedule_category_id', 'id');
    }

    public function master()
    {
        return $this->belongsTo('App\ScheduleMaster', 'schedule_master_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'schedule_user', 'schedule_id', 'user_id');
    }

    public function files()
    {
        return $this->hasMany('App\ScheduleFile', 'schedule_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\ScheduleComment', 'schedule_id', 'id');
    }
}
