<?php

namespace App\Response\HomeworkResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class HomeworkSuccessResponse extends JsonResponse
{
    public function __construct(string $message)
    {
        parent::__construct([
            'success' => true,
            'message' => $message
        ]);
    }
}