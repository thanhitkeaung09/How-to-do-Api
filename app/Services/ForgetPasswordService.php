<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OTP;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordService
{
    public function __construct(
        private EmailLoginService $emailLoginService
    ) {
    }
    public function forget($request)
    {
        $otp = OTP::query()->where("email", $request->email)->first();
        $user = User::query()->where("email", $request->email)->first();
        if ($otp->otp === $request->otp && $otp->expired_at->greaterThan(now())) {
            if ($request->new_password === $request->new_password_confirm) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return "Password is updated successfully";
            } else {
                throw new Exception('Password does not match');
            }
        } else {
            throw new Exception('OTP Code Expired');
        }
    }
}
