<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\RateService;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function __construct(
        private RateService $rateService
    )
    {
    }

    public function ratestore(RateRequest $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->rateService->ratestore($request)
        );
    }

}
