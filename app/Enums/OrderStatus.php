<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Open = 'open';
    case AwaitingPayment = 'awaiting_payment';
    case OpenPaidPercent = 'open_paid_percent';
    case OpenShippedPercent = 'open_shipped_percent';
    case ClosedShipped100 = 'closed_shipped_100';
    case ClosedShippedPercent = 'closed_shipped_percent';
    case Cancelled = 'cancelled';
    case CreatedTransfer = 'created_transfer';
    case InTransit = 'in_transit';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Открыт',
            self::AwaitingPayment => 'Ожидает оплаты',
            self::OpenPaidPercent => 'Открыт. Оплачен %',
            self::OpenShippedPercent => 'Открыт. Отгружен %',
            self::ClosedShipped100 => 'Закрыт. Отгружен 100',
            self::ClosedShippedPercent => 'Закрыт. Отгружен %',
            self::Cancelled => 'Отменен',
            self::CreatedTransfer => 'Создан заказ на перемещение',
            self::InTransit => 'В пути',
        };
    }
}
