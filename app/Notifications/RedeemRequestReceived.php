<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

use App\User;
use App\Redeem;

class RedeemRequestReceived extends Notification
{
    use Queueable;

    public $redeem;

    public function __construct(Redeem $redeem)
    {
        $this->redeem = $redeem;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $url = env('APP_URL');

        $action_url = "{$url}/nova/resources/redeems/{$this->redeem->id}";

        return (new SlackMessage)
            ->content("{$action_url} => new redeem request of Rs. {$this->redeem->amount} has just arrived");
    }
}
