<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HomeworkAccessDeniedException extends AccessDeniedException
{
    private const MESSAGE_DEFAULT = 'Access denied to this homework';
    private const MESSAGE_FOR_TEACHER = 'You cannot modify another teacher\'s homework';
    private const MESSAGE_GROUP_NOT_ASSIGNED = 'You are not assigned to this group';

    public function __construct(string $message = self::MESSAGE_DEFAULT)
    {
        parent::__construct($message);
    }

    public static function forTeacher(): self
    {
        return new self(self::MESSAGE_FOR_TEACHER);
    }

    public static function groupNotAssigned(): self
    {
        return new self(self::MESSAGE_GROUP_NOT_ASSIGNED);
    }
}
