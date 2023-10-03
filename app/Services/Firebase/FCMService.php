<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging as ContractMessaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FCMService
{
    private ContractMessaging $messaging;

    private CloudMessage $message;

    public function __construct(string $token)
    {
        $this->messaging = app('firebase.messaging');

        $this->message = CloudMessage::withTarget('token', $token);
    }

    public static function of($token): self
    {
        return new self($token);
    }

    public function withNotification(string $title, string $body): self
    {
        $this->message = $this->message->withNotification(
            notification: Notification::create($title, $body)
        );

        return $this;
    }

    public function withData(array $data = []): self
    {
        $this->message = $this->message->withData($data);

        return $this;
    }

    public function send()
    {
        try {
            $this->messaging->send(CloudMessage::withTarget('token', $token));
        } catch (Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
                'debug' => $e->getTraceAsString()
            ]);
        }
    }
}
