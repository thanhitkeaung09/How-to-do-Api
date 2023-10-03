<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AdminPostService;
use Illuminate\Http\Request;

class CreatePostController extends Controller
{
    public function __construct(
        private AdminPostService $adminPostService
    )
    {
    }

    public function create_post(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->create_post($request)
        );
    }

    public function view_post(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->view_post($request)
        );
    }

    public function delete_post(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->delete_post($type)
        );
    }

    public function update_post(string $type, Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->update_post($type, $request)
        );
    }

    public function total_posts(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->total_posts()
        );
    }

    public function total_readers(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->total_readers()
        );
    }

    public function like_lists(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->like_lists($type)
        );
    }

    public function read_lists(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminPostService->read_lists($type)
        );
    }


}
