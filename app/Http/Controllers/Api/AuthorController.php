<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        private AuthorService $authorService
    ) {
    }

    public function author_list(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->authorService->author_list()
        );
    }
    public function author_category(string $type)
    {
        return $this->authorService->author_category($type);
    }
}
