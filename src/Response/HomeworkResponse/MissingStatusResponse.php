<?php

namespace App\Response\HomeworkResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MissingStatusResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([
            'success' => false,
            'message' => 'Missing status in request'
        ], Response::HTTP_BAD_REQUEST);
    }
}