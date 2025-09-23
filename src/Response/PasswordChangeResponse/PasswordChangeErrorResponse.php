<?php

namespace App\Response\PasswordChangeResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class PasswordChangeErrorResponse extends JsonResponse
{
    public function __construct(string $message, array $errors = [])
    {
        parent::__construct([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 400);
    }
}