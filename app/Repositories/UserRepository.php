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

    public function getAuth($authUser)
    {
        $user = $this->getUserById($authUser->id);

        return [
            'user' => $user,
            'token' => $user->createToken($user->id)->plainTextToken
        ];;
    }
}
