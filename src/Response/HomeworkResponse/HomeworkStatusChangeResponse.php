<?php

namespace App\Response\HomeworkResponse;

use App\Entity\Homework;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeworkStatusChangeResponse extends JsonResponse
{
    public function __construct(Homework $homework)
    {
        parent::__construct([
            'success' => true,
            'message' => 'Homework status has been changed successfully.',
            'newStatus' => $homework->getStatus()->value,
            'newStatusDisplay' => $homework->getStatus()->getDisplayName()
        ]);
    }
}