<?php

namespace App\Enums;

enum OrderChannel: string
{
    case Manual = 'manual';
    case Matrix = 'matrix';
    case Lazar = 'lazar';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Ручной',
            self::Matrix => 'Матрица',
            self::Lazar => 'Лазарь',
        };
    }
}
