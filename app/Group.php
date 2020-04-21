<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function members()
    {
        return $this->belongsToMany('App\User', 'user_group', 'group_id', 'user_id');
    }

    public static function members_schedules($id, $date)
    {
        $member_schedules = DB::select('SELECT a.id, a.schedule_category_id, a.title, a.type, a.from, a.to, a.from_date, a.to_date, a.public, c.user_id FROM schedules a 
JOIN schedule_user b on a.id = b.schedule_id
JOIN user_group c on b.user_id = c.user_id
WHERE (a.`from` BETWEEN "'.$date.'" AND DATE_ADD("'.$date.'", INTERVAL +6 DAY)
OR a.`to` BETWEEN "'.$date.'" AND DATE_ADD("'.$date.'", INTERVAL +6 DAY)
OR a.`from_date` BETWEEN "'.$date.'" AND DATE_ADD("'.$date.'", INTERVAL +6 DAY)
OR a.`to_date` BETWEEN "'.$date.'" AND DATE_ADD("'.$date.'", INTERVAL +6 DAY))
AND c.group_id = '.$id.'
AND a.state = 1
GROUP BY a.id, c.user_id');
        $member_with_schedules = [];
        foreach ($member_schedules as $member_schedule) {
            $member_with_schedules[$member_schedule->user_id][] = $member_schedule;
        }
        return $member_with_schedules;
    }
}
