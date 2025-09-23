<?php

namespace App\Enum;

enum HomeworkStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case ABANDONED = 'abandoned';

    public function getDisplayName(): string
    {
        return match($this) {
            self::ACTIVE => 'Aktywne',
            self::COMPLETED => 'Ukończone',
            self::ABANDONED => 'Anulowane'
        };
    }
}
