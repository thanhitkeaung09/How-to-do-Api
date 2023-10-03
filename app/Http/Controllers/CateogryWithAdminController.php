<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CateogryWithAdminController extends Controller
{
    public function __construct(
        public CategoryService $categoryService
    ) {
    }
    public function __invoke()
    {
        return $this->categoryService->filter_admin();
    }
}
