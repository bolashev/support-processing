<?php

namespace App\Enums;

enum OrderRequestStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Новый',
            self::InProgress => 'В работе',
            self::Completed => 'Завершен',
        };
    }
}
