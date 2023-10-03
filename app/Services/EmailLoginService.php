<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OTP;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailLoginService
{
    public function __construct(
        private AuthService $authService
    ) {
    }
    public function register($request)
    {
        $check = User::query()->where("email", $request->email)->exists();
        $Otp = $this->generate();
        $this->Otp($request->email, $Otp);

        Mail::to($request->email)->send(new \App\Mail\OtpSend($Otp, $request->name));
        return "Email is Sent";
    }

    public function generate(): string
    {
        try {
            $number = random_int(
                min: 000_000,
                max: 999_999,
            );
        } catch (Throwable $exception) {
            throw new OtpGenerationException('Failed to generate an OTP codes!');
        }

        return str_pad(
            string: strval($number),
            length: 6,
            pad_string: '0',
            pad_type: STR_PAD_LEFT,
        );
    }

    public function Otp($email, $otp)
    {
        OTP::create([
            "email" => $email,
            "otp" => $otp,
            "expired_at" => now()->addMinute(),
        ]);
    }

    public function login($request)
    {
        $user = User::query()->where('email', $request->email)->first();
        $user->device_token = $request->device_token;
        $user->update();
        $this->authService->generateToken($user, $user->email);
        return $user;
    }
}
