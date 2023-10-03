<?php

namespace App\Http\Controllers\Admin;

use App\Dto\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private AuthService  $authService,
        public UserService $userService
    ) {
    }

    public function login(LoginRequest $request, string $type)
    {
        // $exists = DB::table('category_user')->where('user_id', $user->id)->exists();
        $user = $this->authService->login(
            UserData::fromRequest($request->validated(), $type)
        );
        return new ApiSuccessResponse(
            data: $user,
        );
    }

    public function logout(): ApiSuccessResponse
    {
        $this->authService->logout(auth()->user());
        return new ApiSuccessResponse(
            data: true,
            message: __('messages.logout_success'),
            status: Response::HTTP_OK
        );
    }

    public function delete()
    {
        return $this->authService->user_delete();
    }
}
