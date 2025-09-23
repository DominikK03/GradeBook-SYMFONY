<?php

namespace App\Message;

use JsonSerializable;

readonly class NotifyParentMessage implements JsonSerializable
{
    public function __construct(public int $gradeId)
    {
    }
    public function jsonSerialize(): array
    {
        return [
            'gradeId' => $this->gradeId
        ];
    }
}
