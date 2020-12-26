<?php

namespace App\Http\Controllers;

use App\Events\UserWasFollowed;
use App\Http\Requests\ToggleFollow;
use App\Notifications\UserFollowed;
use App\Repositories\UserRepositoryInterface;
use App\User;

class FollowController extends Controller
{
    public $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function toggle(ToggleFollow $request)
    {
        $user = User::find(auth()->id());

        $following = User::find($request->following_id);

        try {
            $this->userRepositoryInterface->toggleFollow($user, $following);

            return response(['success' => true], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
