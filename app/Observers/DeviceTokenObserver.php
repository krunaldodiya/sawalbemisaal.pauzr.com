<?php

namespace App\Observers;

use App\DeviceToken;
use App\Repositories\PushNotificationRepositoryInterface;
use App\User;

class DeviceTokenObserver
{
    public $pushNotificationRepositoryInterface;

    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepositoryInterface)
    {
        $this->pushNotificationRepositoryInterface = $pushNotificationRepositoryInterface;
    }

    public function manageTokens(DeviceToken $deviceToken)
    {
        $user = User::with('device_tokens', 'topics')->find($deviceToken->user_id);

        $user->topics->each(function ($topic) use ($deviceToken) {
            $this->pushNotificationRepositoryInterface->updateTopicSubscriptions($topic, [$deviceToken->token]);
        });
    }

    /**
     * Handle the device token "created" event.
     *
     * @param  \App\DeviceToken  $deviceToken
     * @return void
     */
    public function created(DeviceToken $deviceToken)
    {
        $this->manageTokens($deviceToken);
    }

    /**
     * Handle the device token "updated" event.
     *
     * @param  \App\DeviceToken  $deviceToken
     * @return void
     */
    public function updated(DeviceToken $deviceToken)
    {
        $this->manageTokens($deviceToken);
    }

    /**
     * Handle the device token "deleted" event.
     *
     * @param  \App\DeviceToken  $deviceToken
     * @return void
     */
    public function deleted(DeviceToken $deviceToken)
    {
        //
    }

    /**
     * Handle the device token "restored" event.
     *
     * @param  \App\DeviceToken  $deviceToken
     * @return void
     */
    public function restored(DeviceToken $deviceToken)
    {
        //
    }

    /**
     * Handle the device token "force deleted" event.
     *
     * @param  \App\DeviceToken  $deviceToken
     * @return void
     */
    public function forceDeleted(DeviceToken $deviceToken)
    {
        //
    }
}
