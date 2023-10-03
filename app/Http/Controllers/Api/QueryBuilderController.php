<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class QueryBuilderController extends Controller
{
    public function __construct(
        private QueryBuilderService $builderService
    )
    {
    }

    public function search_keywords(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->builderService->search_keywords($request));
    }

    public function filter_posts(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->builderService->filter_posts($request));
    }

    public function popular_categories(): ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->builderService->popular_categories());
    }
}
