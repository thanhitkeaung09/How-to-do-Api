<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdsRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AdminAdService;
use Illuminate\Http\Request;

class AdminCreateAdController extends Controller
{
    public function __construct(
        public AdminAdService $adminAdService
    ) {
    }

    public function get_ads()
    {
        return new ApiSuccessResponse($this->adminAdService->get_ads());
    }

    public function single_ads(string $type)
    {
        return new ApiSuccessResponse($this->adminAdService->single_ads($type));
    }

    public function create_ads(AdsRequest $request)
    {
        return new ApiSuccessResponse($this->adminAdService->create_ads($request->payload()));
    }

    public function delete_ads(string $type)
    {
        return new ApiSuccessResponse($this->adminAdService->delete_ads($type));
    }

    public function update_ads(string $type, AdsRequest $request)
    {
        return new ApiSuccessResponse($this->adminAdService->update_ads($type, $request));
    }
}
