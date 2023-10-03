<?php

namespace App\Http\Controllers\Admin;

//use App\Services\AdminService;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function __construct(
        private AdminService $service
    )
    {
    }

    public function login(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->service->login($request));
    }

    public function register(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->register($request)
        );
    }

    public function getProfile(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->getProfile($request)
        );
    }

    public function logout(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->logout()
        );
    }

    public function update_username(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->update_username($request)
        );
    }

    public function update_userimage(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->update_userimage($request)
        );
    }

    public function update_password(Request $request):ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->update_password($request)
        );
    }
}
