<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ScheduleCategoryRequest;
use App\Http\Requests\ScheduleRequest;
use App\Group;
use App\Schedule;
use App\ScheduleCategory;
use App\ScheduleComment;
use App\ScheduleFile;
use App\ScheduleMaster;
use Illuminate\Support\Facades\Config;

class ScheduleController extends Controller
{
    const WEEK_DAYS = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    const WEEK_REPEATS = ['', 'first', 'second', 'third', 'fourth', 'last'];

    public function schedule_category_get()
    {
        return response([
            'status' => 'success',
            'categories' => ScheduleCategory::all()
        ]);
    }

    public function schedule_category_add(ScheduleCategoryRequest $request)
    {
        $category = new ScheduleCategory();
        $category->fill($request->toArray());
        $category->save();
        return response([
            'status' => 'success',
            'categories' => ScheduleCategory::all()
        ]);
    }

    public function schedule_category_edit(ScheduleCategoryRequest $request, $id)
    {
        $category = ScheduleCategory::find($id);
        $category->fill($request->toArray());
        $category->save();
        return response([
            'status' => 'success',
            'categories' => ScheduleCategory::all()
        ]);
    }

    public function schedule_category_remove($id)
    {
        $category = ScheduleCategory::find($id);
        if (count($category->schedules) == 0) {
            $category->delete();
            return response([
                'status' => 'success',
                'categories' => ScheduleCategory::all()
            ]);
        } else {
            return self::response_data('has_message', 'このカテゴリーは使われています。');
        }
    }

    public function my_schedule_get()
    {
        return response([
            'status' => 'success',
            'my_schedules' => auth()->user()->my_schedules
        ]);
    }

    public function schedule_detail_get($id)
    {
        return response([
            'status' => 'success',
            'schedule' => Schedule::with('users', 'files', 'comments', 'master')
                ->where('id', $id)
                ->first()
        ]);
    }

    public function group_schedule_get($group_id, $date)
    {
        $group = Group::find($group_id);
        $members = $group->members()->select('id')->orderBy('order')->get()->map(function ($item) { return $item->id; });
        return response([
            'status' => 'success',
            'members' => $members,
            'members_schedules' => Group::members_schedules($group_id, $date)
        ]);
    }

    public function schedule_add(ScheduleRequest $request)
    {
        if ($request->schedule['type'] == 2) {
            $schedule_master = new ScheduleMaster();
            $schedule_master->fill($request->master);
            $schedule_master->timezone = $request->timezone;
            $schedule_master->save();

            self::handleRepeat($request, $schedule_master);

            $schedule = Schedule::where('schedule_master_id', $schedule_master->id)->first();
            self::sendNotifications($schedule->users()->where('id', '<>', $schedule->creator_id)->get(), 'ScheduleCreate', $schedule);
        } else {
            $schedule = new Schedule();
            $schedule->fill($request->schedule);
            if ($request->schedule['type'] == 0) {
                $from = new \DateTime($request->schedule['from'], new \DateTimeZone($request->timezone));
                $to = new \DateTime($request->schedule['to'], new \DateTimeZone($request->timezone));
                $schedule->from = $from->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
                $schedule->to = $to->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
            }
            $schedule->save();

            if (!empty($request->users)) {
                $schedule->users()->attach($request->users);
            }
            if (!empty($request->file('files'))) {
                self::saveFiles($request->file('files'), $schedule->id);
            }
            self::sendNotifications($schedule->users()->where('id', '<>', $schedule->creator_id)->get(), 'ScheduleCreate', $schedule);
        }

        return response([
            'status' => 'success',
            'my_schedules' => auth()->user()->my_schedules
        ]);
    }

    public function schedule_edit(ScheduleRequest $request, $id)
    {
        $schedule = Schedule::find($id);

        if ($request->schedule['type'] == 2) {
            if ($request->edit_type == 0) {
                $schedule->fill($request->schedule);
                $schedule->type = 0;
                $schedule->schedule_master_id = null;
                $from = new \DateTime($request->schedule['from'], new \DateTimeZone($request->timezone));
                $to = new \DateTime($request->schedule['to'], new \DateTimeZone($request->timezone));
                $schedule->from = $from->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
                $schedule->to = $to->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
                $schedule->save();
                $schedule->users()->detach();
                if (!empty($request->users)) {
                    $schedule->users()->attach($request->users);
                }

                self::checkExistedFiles($schedule, empty($request->existed_files) ? [] : $request->existed_files);

                if (!empty($request->file('files'))) {
                    self::saveFiles($request->file('files'), $schedule->id);
                }
                self::sendNotifications($schedule->users()->where('id', '<>', $schedule->editor_id)->get(), 'ScheduleEdit', $schedule);
            } else if ($request->edit_type == 1) {
                $schedule_master = ScheduleMaster::find($schedule->schedule_master_id);
                $schedule_master->to_date = date('Y-m-d', strtotime($request->selected_date.' -1 day'));
                $schedule_master->save();

                DB::table('schedules')->where([
                    ['from', '>', date('Y-m-d H:i:s', strtotime($schedule_master->to_date))],
                    ['schedule_master_id', '=', $schedule_master->id]
                ])->update(['schedule_master_id' => null, 'type' => 0, 'state' => 0, 'deleter_id' => auth()->user()->id]);

                $new_schedule_master = new ScheduleMaster();
                $new_schedule_master->fill($request->master);
                $new_schedule_master->timezone = $request->timezone;
                $new_schedule_master->from_date =  date('Y-m-d', strtotime($request->selected_date));
                $new_schedule_master->save();

                self::handleRepeat($request, $new_schedule_master, true);

                $schedule = Schedule::where('schedule_master_id', $new_schedule_master->id)->first();
                self::sendNotifications($schedule->users()->where('id', '<>', $schedule->editor_id)->get(), 'ScheduleEdit', $schedule);
            } else {
                $schedule_master = ScheduleMaster::find($schedule->schedule_master_id);
                $schedule_master->fill($request->master);
                $schedule_master->timezone = $request->timezone;
                $schedule_master->save();
                DB::table('schedules')->where([
                    ['schedule_master_id', '=', $schedule_master->id]
                ])->update(['schedule_master_id' => null, 'type' => 0, 'state' => 0, 'deleter_id' => auth()->user()->id]);
                self::handleRepeat($request, $schedule_master, true);

                $schedule = Schedule::where('schedule_master_id', $schedule_master->id)->first();
                self::sendNotifications($schedule->users()->where('id', '<>', $schedule->editor_id)->get(), 'ScheduleEdit', $schedule);
            }
        } else {
            $schedule->fill($request->schedule);
            if ($request->schedule['type'] == 0) {
                $from = new \DateTime($request->schedule['from'], new \DateTimeZone($request->timezone));
                $to = new \DateTime($request->schedule['to'], new \DateTimeZone($request->timezone));
                $schedule->from = $from->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
                $schedule->to = $to->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
            }
            $schedule->save();

            $schedule->users()->detach();
            if (!empty($request->users)) {
                $schedule->users()->attach($request->users);
            }

            self::checkExistedFiles($schedule, empty($request->existed_files) ? [] : $request->existed_files);

            if (!empty($request->file('files'))) {
                self::saveFiles($request->file('files'), $schedule->id);
            }
            self::sendNotifications($schedule->users()->where('id', '<>', $schedule->editor_id), 'ScheduleEdit', $schedule);
        }

        return response([
            'status' => 'success',
            'my_schedules' => auth()->user()->my_schedules
        ]);
    }

    public function schedule_remove($id, $type = 0)
    {
        $schedule = Schedule::find($id);
        if ($type == 1 && $schedule->type == 2) {
            $schedule_master = ScheduleMaster::find($schedule->schedule_master_id);
            $schedule_master->to_date = date('Y-m-d', strtotime($schedule->from_date.' -1 day'));
            $schedule_master->save();

            DB::table('schedules')->where([
                ['from', '>', date('Y-m-d H:i:s', strtotime($schedule_master->to_date))],
                ['schedule_master_id', '=', $schedule_master->id]
            ])->update(['schedule_master_id' => null, 'type' => 0, 'state' => 0, 'deleter_id' => auth()->user()->id]);
        } else if ($type == 2 && $schedule->type == 2) {
            DB::table('schedules')->where([
                ['schedule_master_id', '=', $schedule->schedule_master_id]
            ])->update(['schedule_master_id' => null, 'type' => 0, 'state' => 0, 'deleter_id' => auth()->user()->id]);
        } else {
            if ($type == 2) {
                $schedule->type = 0;
            }
            $schedule->state = 0;
            $schedule->deleter_id = auth()->user()->id;
            $schedule->save();
        }

        self::sendNotifications($schedule->users()->where('id', '<>', $schedule->deleter_id), 'ScheduleRemove', auth()->user()->id, $schedule->title);
        return response([
            'status' => 'success',
            'my_schedules' => auth()->user()->my_schedules
        ]);
    }

    public function schedule_comment_add(Request $request, $id)
    {
        $comment = new ScheduleComment();
        $comment->schedule_id = $id;
        $comment->user_id = auth()->user()->id;
        $comment->contents = $request->contents;
        $comment->save();

        $schedule = Schedule::find($id);
        self::sendNotifications($schedule->users()->where('id', '<>', auth()->user()->id), 'ScheduleComment', $schedule, $comment);
        return response([
            'status' => 'success',
            'schedule' => Schedule::with('users', 'files', 'comments')
                ->where('id', $id)
                ->first()
        ]);
    }

    public function schedule_comment_remove($id)
    {
        $schedule_id = ScheduleComment::find($id)->schedule_id;
        ScheduleComment::destroy($id);
        return response([
            'status' => 'success',
            'schedule' => Schedule::with('users', 'files', 'comments')
                ->where('id', $schedule_id)
                ->first()
        ]);
    }

    private static function saveFiles($files, $parent_id, $file_saved = false, $file_urls = [])
    {
        $urls = [];
        foreach ($files as $index => $fileData) {
            $file = new ScheduleFile();
            $file->schedule_id = $parent_id;
            $file->name = $fileData->getClientOriginalName();
            $file->mime_type = $fileData->getMimeType();
            if ($file_saved) {
                $file->url = $file_urls[$index];
                $urls[] = $file_urls[$index];
            } else {
                $file->url = $fileData->store('schedules');
                $urls[] = $file->url;
            }
            $file->size = $fileData->getSize();
            $file->save();
        }
        return $urls;
    }

    private static function checkExistedFiles($schedule, $request_files)
    {
        $db_files = $schedule->files;

        foreach ($db_files as $key => $db_file) {
            if (!in_array($db_file->id, $request_files)) {
                if (self::checkSameFile('schedule_files', $db_file->url, $db_file->id)) {
                    Storage::delete($db_file->url);
                }
                $db_files[$key]->delete();
            }
        }
    }

    private static function handleRepeat($request, $schedule_master, $is_edit = false)
    {
        $begin = new \DateTime($schedule_master->from_date);
        $end = new \DateTime($schedule_master->to_date);
        $file_urls = [];
        if ($schedule_master->repeat_type == 3) {
            $interval = \DateInterval::createFromDateString('1 month');
            $period = new \DatePeriod($begin, $interval, $end);
            foreach ($period as $dt) {
                $day = (int)$dt->format('t') > $schedule_master->monthday ? $dt->format('t') : $schedule_master->monthday;
                $current_date_str = $dt->format('Y-m-').$day.' ';
                $current_date = new \DateTime($current_date_str);
                if ($current_date >= $begin && $current_date <= $end) {
                    $file_urls = self::createSchedule($request, $current_date_str, $schedule_master, $file_urls, $is_edit);
                }
            }
        } else if ($schedule_master->repeat_type == 2) {
            if ($schedule_master->week_repeat == 0) {
                $interval = \DateInterval::createFromDateString('1 week');
                if ($begin->format('w') != $schedule_master->weekday) {
                    $begin->modify('next '.self::WEEK_DAYS[$schedule_master->weekday]);
                }
                if ($end->format('w') != $schedule_master->weekday) {
                    $end->modify('last '.self::WEEK_DAYS[$schedule_master->weekday]);
                }
                $period = new \DatePeriod($begin, $interval, $end);
                foreach ($period as $dt) {
                    $file_urls = self::createSchedule($request, $dt->format('Y-m-d '), $schedule_master, $file_urls, $is_edit);
                }

            } else {
                $interval = \DateInterval::createFromDateString('1 month');
                $period = new \DatePeriod($begin, $interval, $end);
                foreach ($period as $dt) {
                    $current_date = new \DateTime(self::WEEK_REPEATS[$schedule_master->week_repeat].' '.self::WEEK_DAYS[$schedule_master->weekday].' of '.$dt->format('Y-m'));
                    if ($current_date >= $begin && $current_date <= $end) {
                        $file_urls = self::createSchedule($request, $current_date->format('Y-m-d '), $schedule_master, $file_urls, $is_edit);
                    }
                }
            }
        } else {
            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);
            foreach ($period as $dt) {
                $weekday = $dt->format('w');
                if ($schedule_master->repeat_type == 0 || ($weekday > 0 && $weekday < 6)) {
                    $file_urls = self::createSchedule($request, $dt->format('Y-m-d '), $schedule_master, $file_urls, $is_edit);
                }
            }
        }
    }

    private static function createSchedule($request, $date_str, $master, $file_urls = [], $is_edit = false)
    {
        $schedule = new Schedule();
        $schedule->fill($request->schedule);
        $schedule->schedule_master_id = $master->id;
        $from = new \DateTime($date_str.$master->from_time, new \DateTimeZone($request->timezone));
        $to = new \DateTime($date_str.$master->to_time, new \DateTimeZone($request->timezone));
        $schedule->from = $from->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
        $schedule->to = $to->setTimezone(new \DateTimeZone(Config::get('app.timezone')))->format('Y-m-d H:i:s');
        $schedule->save();

        if (!empty($request->users)) {
            $schedule->users()->attach($request->users);
        }

        if ($is_edit && !empty($request->existed_files)) {
            foreach ($request->existed_files as $fid) {
                $existed_file = ScheduleFile::find($fid);
                $new_file = new ScheduleFile();
                $new_file->schedule_id = $schedule->id;
                $new_file->name = $existed_file->name;
                $new_file->mime_type = $existed_file->mime_type;
                $new_file->url = $existed_file->url;
                $new_file->size = $existed_file->size;
                $new_file->save();
            }
        }

        $file_urls_new = [];
        if (!empty($request->file('files'))) {
            if (count($file_urls) > 0) {
                self::saveFiles($request->file('files'), $schedule->id, true, $file_urls);
            } else {
                $file_urls_new = self::saveFiles($request->file('files'), $schedule->id);
            }
        }
        return $file_urls_new;
    }
}
