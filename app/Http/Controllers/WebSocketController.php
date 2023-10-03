<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiSuccessResponse;
use App\Services\WebSocketService;
use Illuminate\Http\Request;

class WebSocketController extends Controller
{
    public function __construct(
        private WebSocketService $webSocketService
    ) {
    }
    public function generate_url(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->webSocketService->generate_url()
        );
    }
}
