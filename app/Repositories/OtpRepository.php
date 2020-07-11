<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class OtpRepository implements OtpRepositoryInterface
{
    public $base_url = "https://control.msg91.com/api";

    private function generateUrl($type, $country, $mobile, $otp, $message)
    {
        $authKey = env('MSG91_KEY');

        $mwc = $country['phonecode'] . $mobile;

        if ($type == 'request_otp') {
            return "{$this->base_url}/sendotp.php?authkey=$authKey&mobile=$mwc&otp=$otp&message=$message";
        }

        if ($type == 'verify_otp') {
            return "{$this->base_url}/verifyRequestOTP.php?authkey=$authKey&mobile=$mwc&otp=$otp";
        }
    }

    public function requestOtp($country, $mobile, $otp, $message)
    {
        try {
            $url = $this->generateUrl("request_otp", $country, $mobile, $otp, $message);
            $response = Http::get($url);
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function verifyOtp($country, $mobile, $otp)
    {
        try {
            $url = $this->generateUrl("verify_otp", $country, $mobile, $otp, null);
            $response = Http::get($url);
            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
