<?php

namespace App\Enums;

enum ClientType: string
{
    case Network = 'network';
    case Dealer = 'dealer';
    case Retail = 'retail';

    public function label(): string
    {
        return match ($this) {
            self::Network => 'Сеть',
            self::Dealer => 'Дилер',
            self::Retail => 'Розница',
        };
    }
}
