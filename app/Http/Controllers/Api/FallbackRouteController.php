<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FallbackRouteController extends Controller
{
    public function __invoke():ApiErrorResponse
    {
        return new ApiErrorResponse(
            message:__('messages.route_not_found'),
            status: Response::HTTP_NOT_FOUND
        );
    }
}
