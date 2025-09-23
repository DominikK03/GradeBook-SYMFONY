<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GradeAccessDeniedException extends AccessDeniedException
{
    private const MESSAGE_DEFAULT = 'Access denied to this grade';
    private const MESSAGE_FOR_TEACHER = 'You cannot modify another teacher\'s grade';

    public function __construct(string $message = self::MESSAGE_DEFAULT)
    {
        parent::__construct($message);
    }

    public static function forTeacher(): self
    {
        return new self(self::MESSAGE_FOR_TEACHER);
    }
}
