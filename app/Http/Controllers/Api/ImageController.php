<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileStorage\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function __construct(
        private FileStorageService $service,
    )
    {
    }

    public function show($path):Response
    {
        return $this->service->display($path);
    }
}
