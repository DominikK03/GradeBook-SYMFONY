<?php

namespace App\Response\HomeworkResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeworkErrorResponse extends JsonResponse
{
    public function __construct(string $message, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}