<?php

namespace App\Message;

class NotifyStudentsMessage implements \JsonSerializable
{

    public function __construct(public int $homeworkID)
    {
    }
    public function jsonSerialize(): array
    {
        return [
          "homeworkID" => $this->homeworkID
        ];
    }
}
