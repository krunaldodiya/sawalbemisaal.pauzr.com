<?php

namespace App\Listeners;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\User;

class FollowAdmin implements ShouldQueue
{
    public $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $author = User::where('email', 'Antriksh93@gmail.com')->first();
        $user = User::find($event->user->id);

        $this->userRepositoryInterface->toggleFollow($user, $author);
    }
}
