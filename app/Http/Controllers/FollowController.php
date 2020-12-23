<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToggleFollow;
use App\Notifications\UserFollowed;
use App\User;

class FollowController extends Controller
{
    public function toggle(ToggleFollow $request)
    {
        $user = User::find(auth()->id());

        $following = User::find($request->following_id);

        try {
            $follow = $user->followings()->toggle($following->id);

            if (empty($follow['detached'])) {
                $following->notify(new UserFollowed($following->toArray(), $user->toArray()));
            }

            if (empty($follow['attached'])) {
                $following->notifications()
                    ->where('data->following_id', $following->id)
                    ->where('data->follower_id', $user->id)
                    ->delete();
            }

            return response(['success' => true], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
