<?php

namespace App\Repositories;

use App\User;

class UserRepository implements UserRepositoryInterface
{
    public function getUserById($user_id)
    {
        return User::with('country', 'wallet.transactions')
            ->where('id', $user_id)
            ->first();
    }

    public function getAuth($mobile, $country_id)
    {
        $user = User::firstOrCreate(['mobile' => $mobile, 'country_id' => $country_id]);

        return [
            'user' => $user,
            'token' => $user->createToken($user->id)->plainTextToken
        ];
    }
}
