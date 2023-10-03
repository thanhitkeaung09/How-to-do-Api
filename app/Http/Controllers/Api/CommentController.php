<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private CommentService $commentService
    )
    {
    }

    public function commentStore(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->commentService->commentStore($request)
        );
    }

    public function commentLikeOrNot(Request $request , string $type)
    {
        return new ApiSuccessResponse(
            $this->commentService->commentLikeOrNot($request , $type)
        );
    }
}
