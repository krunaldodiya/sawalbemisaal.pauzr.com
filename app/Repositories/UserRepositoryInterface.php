<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getUserById($user_id);
    public function getAuth($mobile, $country_id);
}
