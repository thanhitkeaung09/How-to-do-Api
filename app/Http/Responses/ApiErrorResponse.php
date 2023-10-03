<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiErrorResponse implements Responsable
{
    public function __construct(
        protected string $message,
        protected int $status = HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
        protected array $headers = [],
        protected ?Throwable $e = null,

    ) {
    }

    public function toResponse($request): Response
    {
        $response['message'] = $this->message;
        $response['status'] = $this->status;

        if ($this->e && config('app.debug')) {
            $response['debug'] = [
                'message' => $this->e->getMessage(),
                'file' => $this->e->getFile(),
                'line' => $this->e->getLine(),
                'trace' => $this->e->getTraceAsString(),
            ];
        }

        return response()->json(
            data: $response,
            status: $this->status,
            headers: $this->headers,
        );
    }
}
