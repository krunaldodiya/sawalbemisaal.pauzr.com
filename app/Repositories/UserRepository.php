<?php

namespace App\Repositories;

use App\Events\UserWasFollowed;
use App\User;

class UserRepository implements UserRepositoryInterface
{
    public function toggleFollow($user, $following)
    {
        $follow = $user->followings()->toggle($following->id);

        if (empty($follow['detached'])) {
            event(new UserWasFollowed($following, $user));
        }

        if (empty($follow['attached'])) {
            $following->notifications()
                ->where('data->following_id', $following->id)
                ->where('data->follower_id', $user->id)
                ->delete();
        }
    }

    public function getUserById($user_id)
    {
        return User::with('country', 'wallet.transactions', 'followers', 'followings')
            ->where('id', $user_id)
            ->first();
    }

    public function getAuth($mobile, $country_id)
    {
        $auth = User::firstOrCreate(['mobile' => $mobile, 'country_id' => $country_id]);

        $user = $this->getUserById($auth->id);

        return [
            'user' => $user,
            'token' => $user->createToken($user->id)->plainTextToken
        ];
    }
}
