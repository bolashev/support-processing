<?php

namespace App\Enums;

enum ProcessingDirection: string
{
    case Network = 'network';
    case Dealer = 'dealer';
    case Retail = 'retail';
    case All = 'all';

    public const array GROUP_MAP = [
        61 => self::Network,
        59 => self::Dealer,
        60 => self::Retail,
        62 => self::All,
    ];

    public function label(): string
    {
        return match ($this) {
            self::Network => 'Сетевое',
            self::Dealer => 'Дилерское',
            self::Retail => 'Розничное',
            self::All => 'Единое окно процессинга',
        };
    }

    public static function fromPortalGroups(array $groupIds): array
    {
        $seen = [];
        $directions = [];

        foreach ($groupIds as $groupId) {
            if (isset(self::GROUP_MAP[$groupId]) && ! in_array($groupId, $seen, true)) {
                $seen[] = $groupId;
                $directions[] = self::GROUP_MAP[$groupId];
            }
        }

        return $directions;
    }
}
