<?php

namespace App\Http\Controllers;

use App\Dto\AppVersionData;
use App\Http\Requests\AppVersionRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AppVersionService;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function __construct(
        private AppVersionService $service
    ) {
    }
    public function update(AppVersionRequest $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->update($request)
        );
    }

    public function get(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->service->get()
        );
    }
}
