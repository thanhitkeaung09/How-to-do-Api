<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\OTP;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OTPConfirmController extends Controller
{
    public function __construct(
        private OTPService $oTPService
    ) {
    }
    public function confirm(Request $request)
    {
        $exists = User::query()->where("email", $request->email)->first();
        if ($exists) {
            return new ApiErrorResponse(
                message: "Account is already confirmed",
                status: 403
            );
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password === $request->confirm_password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $otp_code = $request->otp;
            $confirm = OTP::query()->where("otp", $otp_code)->exists();
            $otp = OTP::query()->where("otp", $otp_code)->first();

            if ($confirm && $otp->expired_at->greaterThan(now())) {
                return new ApiSuccessResponse($this->oTPService->confirm($request));
            } else {
                return new ApiErrorResponse(
                    message: "OTP Code is Expired",
                    status: 498
                );
            }
        }
    }

    public function otp_password_confirm(Request $request)
    {
        return $this->oTPService->otp_password_confirm($request);
    }
}
