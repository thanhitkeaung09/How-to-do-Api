<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AdminCommentService;
use Illuminate\Http\Request;

class CreateCommentController extends Controller
{
    public function __construct(
        private AdminCommentService $commentService
    )
    {
    }

    public function show_comments(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->commentService->view_all_comments($type)
        );
    }

    public function delete_comment(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->commentService->delete_comment($type)
        );
    }

    public function update_comment(string $type , Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->commentService->update_comment($type , $request)
        );
    }

}
