<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleFile extends Model
{
    protected $guarded = [];

    public function schedule()
    {
        return $this->belongsTo('App\Schedule', 'schedule_id', 'id');
    }
}
