<?php

declare(strict_types=1);

namespace App\Services;

use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class WebSocketService
{
    public function generate_url()
    {
        return WebSocketsRouter::webSocketUrl();
    }
}
