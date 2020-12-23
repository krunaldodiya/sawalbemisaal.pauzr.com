<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    public $following;
    public $follower;

    public function __construct($following, $follower)
    {
        $this->following = $following;
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'following_id' => $this->following['id'],
            'follower_id' => $this->follower['id'],
            'following' => $this->following,
            'follower' => $this->follower,
        ];
    }
}
