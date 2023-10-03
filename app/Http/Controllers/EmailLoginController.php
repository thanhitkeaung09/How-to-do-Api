<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailDataRequest;
use App\Http\Requests\EmailLoginDataRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use App\Services\EmailLoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmailLoginController extends Controller
{
    public function __construct(
        private EmailLoginService $emailLoginService
    ) {
    }

    public function register(EmailLoginDataRequest $request)
    {
        $user = User::query()->where('email', $request->email)->exists();
        if ($user) {
            return new ApiErrorResponse(
                status: 409,
                message: "User Already Exists",
            );
        } else {
            if ($request->password === $request->confirm_password) {
                return new ApiSuccessResponse($this->emailLoginService->register($request->payload()));
            } else {
                return new ApiErrorResponse(
                    status: 415,
                    message: "Register Fail",
                );
            }
        }
    }

    public function login(EmailDataRequest $request)
    {
        $user = User::query()->where('email', $request->email)->first();
        $exists = DB::table('category_user')->where('user_id', $user->id)->exists();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return new ApiSuccessResponse($this->emailLoginService->login($request));
            } else {
                return new ApiErrorResponse(
                    message: "Login Fail",
                    status: 401
                );
            }
        } else {
            return new ApiErrorResponse(
                message: "Account does not exist",
                status: 404
            );
        }
    }
}
