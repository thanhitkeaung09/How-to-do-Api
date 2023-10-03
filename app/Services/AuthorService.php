<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Responses\ApiSuccessResponse;
use App\Models\Admin;
use App\Models\Category;

class AuthorService
{
    public function author_list()
    {
        return Admin::all();
    }

    public function author_category($type)
    {
        $category = Category::with('admin')->find($type);
        return new ApiSuccessResponse($category);
    }
}
