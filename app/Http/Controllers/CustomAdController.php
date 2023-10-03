<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiSuccessResponse;
use App\Services\AdService;
use Illuminate\Http\Request;

class CustomAdController extends Controller
{
    public function __construct(
        public AdService $adService
    ) {
    }
    public function __invoke()
    {
        return new ApiSuccessResponse($this->adService->get_ads());
    }
}
