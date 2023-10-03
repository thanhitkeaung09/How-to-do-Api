<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;

class OTPService
{
    public function __construct(
        public AuthService $authService,
        public EmailLoginService $emailLoginService,
    ) {
    }
    public function confirm($request)
    {
        $otp_code = $request->otp;
        $confirm = OTP::query()->where("otp", $otp_code)->exists();
        $otp = OTP::query()->where("otp", $otp_code)->first();
        $user = User::query()->where("email", $otp->email)->first();
        return $this->authService->generateToken($user, $otp_code);
    }

    public function resend($request)
    {
        $otp = OTP::query()->where("email", $request->email)->first();
        $user = User::query()->where('email', $otp->email)->first();
        $new_otp = $this->emailLoginService->generate();
        $otp->update(['otp' => $new_otp, "expired_at" => now()->addMinute()]);
        Mail::to($otp->email)->send(new \App\Mail\OtpSend($new_otp, "User"));
        return "OTP Code is resend";
    }

    public function otp_password_confirm($request)
    {
        $otp_code = $request->otp;
        $confirm = OTP::query()->where("otp", $otp_code)->exists();
        $otp = OTP::query()->where("otp", $otp_code)->first();

        if ($confirm && $otp->expired_at->greaterThan(now())) {
            return new ApiSuccessResponse($this->confirm($request));
        } else {
            return new ApiErrorResponse(
                message: "OTP Code is Expired",
                status: 498
            );
        }
    }
}
