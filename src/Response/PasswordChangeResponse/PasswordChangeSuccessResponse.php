<?php

namespace App\Response\PasswordChangeResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class PasswordChangeSuccessResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([
            'success' => true,
            'message' => 'Password has been changed successfully'
        ]);
    }
}