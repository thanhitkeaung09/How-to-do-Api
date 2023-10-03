<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    )
    {
    }

    public function notiList():ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->notificationService->notiList());
    }
}
