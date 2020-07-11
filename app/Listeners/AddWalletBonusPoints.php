<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddWalletBonusPoints
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        $points = config('points.user_registered');

        $transaction = $user->createTransaction($points['points'], 'deposit', [
            'points' => [
                'id' => $user->id,
                'type' => $points['type']
            ]
        ]);

        $user->deposit($transaction->transaction_id);
    }
}
