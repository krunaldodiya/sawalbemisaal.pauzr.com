<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login;
use App\Http\Requests\Register;

use App\Repositories\UserRepositoryInterface;

use App\User;

class AuthController extends Controller
{
    public $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function getUsernameKey($username)
    {
        $field = 'username';

        if (is_numeric($username)) {
            $field = 'mobile';
        }

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }

        return $field;
    }



    public function login(Login $request)
    {
        $usernameKey = $this->getUsernameKey($request->username);

        try {
            auth()->attempt([
                $usernameKey => $request->username,
                'password' => $request->password
            ]);

            $auth = $this->userRepositoryInterface->getAuth(auth()->user());

            return response(['user' => $auth['user'], 'token' => $auth['token']]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function register(Register $request)
    {
        try {
            $create = User::create([
                'mobile' => $request->mobile,
                'country_id' => $request->country_id,
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);

            $auth = $this->userRepositoryInterface->getAuth($create);

            return response(['user' => $auth['user'], 'token' => $auth['token']]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
