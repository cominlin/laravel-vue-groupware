<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleComment extends Model
{
    protected $guarded = [];

    public function schedule()
    {
        return $this->belongsTo('App\Schedule', 'schedule_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
