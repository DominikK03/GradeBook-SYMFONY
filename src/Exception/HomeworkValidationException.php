<?php

namespace App\Exception;

use InvalidArgumentException;

class HomeworkValidationException extends InvalidArgumentException
{
    private const MESSAGE_EMPTY_TOPIC = 'Homework topic cannot be empty';
    private const MESSAGE_INVALID_DUE_DATE = 'End date must be in the future';
    private const MESSAGE_NO_GROUP_SELECTED = 'You must select a group for the homework';
    private const MESSAGE_TOPIC_TOO_LONG = 'Homework topic is too long (maximum 255 characters)';
    private const MESSAGE_DESCRIPTION_TOO_LONG = 'Homework description is too long (maximum 255 characters)';

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function emptyTopic(): self
    {
        return new self(self::MESSAGE_EMPTY_TOPIC);
    }

    public static function invalidDueDate(): self
    {
        return new self(self::MESSAGE_INVALID_DUE_DATE);
    }

    public static function noGroupSelected(): self
    {
        return new self(self::MESSAGE_NO_GROUP_SELECTED);
    }

    public static function topicTooLong(): self
    {
        return new self(self::MESSAGE_TOPIC_TOO_LONG);
    }

    public static function descriptionTooLong(): self
    {
        return new self(self::MESSAGE_DESCRIPTION_TOO_LONG);
    }
}
