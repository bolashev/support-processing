<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'request_status' => ['nullable', 'string', 'in:new,in_progress,completed'],
            'manager_ids' => ['nullable', 'array'],
            'manager_ids.*' => ['integer', 'exists:users,id'],
            'shipped_sort' => ['nullable', 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
