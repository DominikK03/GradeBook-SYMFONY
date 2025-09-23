<?php

namespace App\Response\PasswordChangeResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class PasswordChangeValidationErrorResponse extends JsonResponse
{
    public function __construct(array $errors)
    {
        parent::__construct([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422);
    }
}