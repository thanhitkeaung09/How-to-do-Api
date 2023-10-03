<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiSuccessResponse;
use App\Services\OTPService;
use Illuminate\Http\Request;

class OTPResendController extends Controller
{
    public function __construct(
        private OTPService $oTPService
    ) {
    }
    public function resend(Request $request)
    {
        return new ApiSuccessResponse($this->oTPService->resend($request));
    }
}
