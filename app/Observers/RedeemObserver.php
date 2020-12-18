<?php

namespace App\Observers;

use App\Redeem;
use App\Repositories\PushNotificationRepositoryInterface;

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
        //
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
                'title_key' => 'will_start_in_few_minutes_title',
                'body_key' => 'will_start_in_few_minutes_body',
                'title' => "test will start in few minutes",
                'body' => "Everyone is preparing, are you?",
                'image' => url('images/notify_soon.jpg'),
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
