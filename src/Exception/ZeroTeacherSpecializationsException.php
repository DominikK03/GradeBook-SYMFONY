<?php

namespace App\Exception;

class ZeroTeacherSpecializationsException extends \Exception
{
    private const MESSAGE = 'Teacher has no assigned specialization';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
