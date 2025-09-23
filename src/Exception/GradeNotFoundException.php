<?php

namespace App\Exception;

use Exception;
use Throwable;

class GradeNotFoundException extends Exception
{
    public function __construct(string $message = 'Grade not found')
    {
        parent::__construct($message);
    }

    public static function withId(int $gradeId): self
    {
        return new self("Grade with ID $gradeId not found");
    }
}
