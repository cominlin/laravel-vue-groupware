<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ScheduleRemove extends Notification
{
    use Queueable;
    public $user;
    public $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $title)
    {
        $this->user = $user;
        $this->title = $title;
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
            'title' => $this->title,
            'user_id' => $this->user->id,
            'url' => '/schedule',
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
                'title' => $this->title,
                'user_id' => $this->user->id,
                'url' => '/schedule'
            ]
        ]);
    }
}
