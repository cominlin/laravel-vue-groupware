<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ScheduleComment extends Notification
{
    use Queueable;
    public $schedule;
    public $schedule_comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($schedule, $schedule_comment)
    {
        $this->schedule = $schedule;
        $this->schedule_comment = $schedule_comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the db representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->schedule->title,
            'user_id' => $this->schedule_comment->user_id,
            'url' => '/schedule/detail/'.$this->schedule->id,
            'content' => mb_substr($this->schedule_comment->contents, 0, 10).'...'
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'title' => $this->schedule->title,
                'user_id' => $this->schedule_comment->user_id,
                'url' => '/schedule/detail/'.$this->schedule->id,
                'content' => mb_substr($this->schedule_comment->contents, 0, 10).'...'
            ]
        ]);
    }
}
