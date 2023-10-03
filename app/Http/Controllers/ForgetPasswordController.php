<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiSuccessResponse;
use App\Services\ForgetPasswordService;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function __construct(
        private ForgetPasswordService $forgetPasswordService
    ) {
    }
    public function __invoke(Request $request)
    {
        return new ApiSuccessResponse($this->forgetPasswordService->forget($request));
    }
}
