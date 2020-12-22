<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class RedeemRequestReceived extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $url = env('APP_URL');

        $action_url = "{$url}/nova/resources/redeems/{$notifiable->id}";

        return (new SlackMessage)
            ->content("{$action_url} => new redeem request of Rs. {$notifiable->amount} has just arrived");
    }
}
