<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CreateCategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    )
    {
    }

    public function create_category(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->categoryService->create_category($request)
        );
    }

    public function view_allcategory(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->categoryService->view_allcategory()
        );
    }

    public function delete_category(string $type)
    {
        return new ApiSuccessResponse(
            $this->categoryService->delete_category($type)
        );
    }

    public function update_category(string $type, Request $request)
    {
//        return $request;
        return new ApiSuccessResponse(
            $this->categoryService->update_category($type, $request)
        );
    }

    public function search_category(Request $request)
    {
        return new ApiSuccessResponse(
            $this->categoryService->search_category($request)
        );
    }
}
