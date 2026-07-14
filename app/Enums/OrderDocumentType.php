<?php

namespace App\Enums;

enum OrderDocumentType: string
{
    case Invoice = 'invoice';
    case ExpenseNote = 'expense_note';
    case ShipmentRequest = 'shipment_request';
    case TransferOrder = 'transfer_order';
    case TransferGoods = 'transfer_goods';
    case InvoiceFactura = 'invoice_factura';
    case Package = 'package';

    public function label(): string
    {
        return match ($this) {
            self::Invoice => 'Счет',
            self::ExpenseNote => 'Расходная накладная',
            self::ShipmentRequest => 'Заявка на отгрузку',
            self::TransferOrder => 'Заказ на перемещение',
            self::TransferGoods => 'Перемещение товаров',
            self::InvoiceFactura => 'Счет-фактура',
            self::Package => 'Пакет документов',
        };
    }
}
