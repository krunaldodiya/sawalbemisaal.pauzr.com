<?php

namespace App\Observers;

use App\Notifications\RedeemRequestReceived;
use App\User;
use App\Redeem;
use App\Repositories\PushNotificationRepositoryInterface;
use Illuminate\Support\Facades\Notification;

class RedeemObserver
{
    public $pushNotificationRepositoryInterface;

    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepositoryInterface)
    {
        $this->pushNotificationRepositoryInterface = $pushNotificationRepositoryInterface;
    }

    /**
     * Handle the redeem "created" event.
     *
     * @param  \App\Redeem  $redeem
     * @return void
     */
    public function created(Redeem $redeem)
    {
        $redeem->user->notify(new RedeemRequestReceived($redeem));
    }

    /**
     * Handle the redeem "updated" event.
     *
     * @param  \App\Redeem  $redeem
     * @return void
     */
    public function updated(Redeem $redeem)
    {
        $user = $redeem->user;

        if ($redeem->status === 'success') {
            $this->pushNotificationRepositoryInterface->notify("/topics/user_{$user->id}", [
                'title_key' => 'Congratulations! Amount Transferred!',
                'body_key' => "Rs. {$redeem->amount} transferred to your {$redeem->gateway} account. Congrats!",
                'title' => "Congratulations! Amount Transferred!",
                'body' => "Rs. {$redeem->amount} transferred to your {$redeem->gateway} account. Congrats!",
                'image' => url('images/payment_success.jpg'),
                'show_alert_box' => false
            ]);
        }
    }

    /**
     * Handle the redeem "deleted" event.
     *
     * @param  \App\Redeem  $redeem
     * @return void
     */
    public function deleted(Redeem $redeem)
    {
        //
    }

    /**
     * Handle the redeem "restored" event.
     *
     * @param  \App\Redeem  $redeem
     * @return void
     */
    public function restored(Redeem $redeem)
    {
        //
    }

    /**
     * Handle the redeem "force deleted" event.
     *
     * @param  \App\Redeem  $redeem
     * @return void
     */
    public function forceDeleted(Redeem $redeem)
    {
        //
    }
}
