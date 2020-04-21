<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ScheduleCreate extends Notification
{
    use Queueable;
    public $schedule;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($schedule)
    {
        $this->schedule = $schedule;
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
        $content = empty($this->schedule->memo) ? '' : mb_substr($this->schedule->memo, 0, 10).'...';
        return [
            'title' => $this->schedule->title,
            'user_id' => $this->schedule->creator_id,
            'url' => '/schedule/'.$this->schedule->id,
            'content' => $content
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
        $content = empty($this->schedule->memo) ? '' : mb_substr($this->schedule->memo, 0, 10).'...';
        return new BroadcastMessage([
            'data' => [
                'title' => $this->schedule->title,
                'user_id' => $this->schedule->creator_id,
                'url' => '/schedule/'.$this->schedule->id,
                'content' => $content
            ]
        ]);
    }
}
