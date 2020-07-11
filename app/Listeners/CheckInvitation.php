<?php

namespace App\Listeners;

use App\Invitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckInvitation
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
        $points = config('points.invitation');

        $receiver = $event->user;

        $invitation = Invitation::with('sender')
            ->where(['mobile' => $receiver['mobile']])
            ->first();

        if ($invitation) {
            collect([$receiver, $invitation->sender])->each(function ($user) use ($points) {
                $transaction = $user->createTransaction($points['points'], 'deposit', [
                    'points' => [
                        'id' => $user->id,
                        'type' => $points['type']
                    ]
                ]);

                $user->deposit($transaction->transaction_id);
            });
        }
    }
}
