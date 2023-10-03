<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
    )
    {
    }

    public function getAllCategory():ApiSuccessResponse
    {
        $category = $this->categoryService->allCategories();
        return new ApiSuccessResponse($category);
    }

    public function showUserCategory():ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->categoryService->showUserCategory());
    }

    public function storeUserCategory( Request $request ):ApiSuccessResponse
    {
        $userCategories = $this->categoryService->storeUserCategory($request);
        return new ApiSuccessResponse($userCategories);
    }
}
