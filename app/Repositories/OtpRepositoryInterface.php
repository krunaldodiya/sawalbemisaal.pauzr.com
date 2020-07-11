<?php

namespace App\Repositories;

interface OtpRepositoryInterface
{
    public function requestOtp($country, $mobile, $otp, $message);
    public function verifyOtp($country, $mobile, $otp);
}
