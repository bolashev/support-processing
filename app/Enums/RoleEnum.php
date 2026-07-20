<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Root = 'root';
    case Admin = 'admin';
    case SupportManager = 'support_manager';

    public function label(): string
    {
        return match ($this) {
            self::Root => 'Root',
            self::Admin => 'Администратор',
            self::SupportManager => 'Менеджер поддержки',
        };
    }
}
