<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestOtp;
use App\Http\Requests\VerifyOtp;

use App\Repositories\OtpRepositoryInterface;
use App\Repositories\UserRepositoryInterface;

use App\Country;
use App\User;
use Error;

class OtpController extends Controller
{
    public $otpRepositoryInterface;
    public $userRepositoryInterface;

    public function __construct(OtpRepositoryInterface $otpRepositoryInterface, UserRepositoryInterface $userRepositoryInterface)
    {
        $this->otpRepositoryInterface = $otpRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function requestOtp(RequestOtp $request)
    {
        $mobile = $request->mobile;

        $country_id = $request->country_id;

        $user = User::where('mobile', $mobile)->first();

        $country = Country::find($country_id);

        if (!$user) {
            throw new Error("User does not exists", 404);
        }

        $otp = mt_rand(1000, 9999);

        $message = "$otp is Your otp for phone verification";

        $requestOtp = $this->otpRepositoryInterface->requestOtp($country, $mobile, $otp, $message);

        if ($requestOtp['type'] === "error") {
            return response(["success" => false, "error" => $requestOtp['message']], 400);
        }

        return response(["success" => true, "otp" => $otp], 200);
    }

    public function verifyOtp(VerifyOtp $request)
    {
        $mobile = $request->mobile;

        $country_id = $request->country_id;

        $user = User::where('mobile', $mobile)->first();

        $country = Country::find($country_id);

        $verifyOtp = $this->otpRepositoryInterface->verifyOtp($country, $request->mobile, $request->otp);

        if ($verifyOtp['type'] === "error") {
            return response(["success" => false, "error" => $verifyOtp['message']], 400);
        }

        $auth = $this->userRepositoryInterface->getAuth($user);

        return response(["success" => true, "token" => $auth['token'], "user" => $auth['user']], 200);
    }
}
