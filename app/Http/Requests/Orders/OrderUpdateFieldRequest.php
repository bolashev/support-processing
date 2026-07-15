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
            'field' => ['required', 'string', 'in:payment_method,delivery_method,client_phone,client_email,reserve_range,counterparty_name'],
            'value' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('field') === 'client_email' && $this->input('value') !== null) {
                if (! filter_var($this->input('value'), FILTER_VALIDATE_EMAIL)) {
                    $validator->errors()->add('value', 'Некорректный формат email');
                }
            }
        });
    }
}
