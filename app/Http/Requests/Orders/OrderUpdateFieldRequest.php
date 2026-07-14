<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'field' => ['required', 'string', 'in:payment_method,delivery_method,client_phone,client_email,reserve_date,counterparty_name'],
            'value' => ['nullable', 'string', 'max:500'],
        ];
    }
}
