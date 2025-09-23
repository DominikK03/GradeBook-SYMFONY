<?php

namespace App\Response\HomeworkResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidFormDataResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([
            'success' => false,
            'message' => 'Invalid form data.'
        ], Response::HTTP_BAD_REQUEST);
    }
}