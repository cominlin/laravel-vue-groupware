<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleCategory extends Model
{
    protected $guarded = [];

    public function schedules()
    {
        return $this->hasMany('App\Schedule', 'schedule_category_id', 'id');
    }
}
