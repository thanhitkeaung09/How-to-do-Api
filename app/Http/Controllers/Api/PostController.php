<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService
    ) {
    }

    public function postSaveOrNot(Request $request, string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->postService->postSaveOrNot($request, $type)
        );
    }

    public function postList(Request $request)
    {
        return new PaginatedResourceResponse(
            PostResource::collection($this->postService->postList($request))
        );
    }

    public function postShare(Request $request, string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->postService->shareArticle($request, $type)
        );
    }

    public function shareList(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->postService->shareList()
        );
    }

    public function postDetail(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->postService->postDetail($type)
        );
    }

    public function postDetailComment(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->postService->postDetailComment($type));
    }

    public function like_posts(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->postService->like_articles()
        );
    }

    public function user_author_follow(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->postService->user_author_follow()
        );
    }
}
