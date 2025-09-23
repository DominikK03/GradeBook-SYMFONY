<?php

namespace App\Response\HomeworkResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeworkNotFoundResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([
            'success' => false,
            'message' => 'Homework not found.'
        ], Response::HTTP_NOT_FOUND);
    }
}