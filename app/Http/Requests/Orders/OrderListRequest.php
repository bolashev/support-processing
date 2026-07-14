<?php

namespace App\Http\Requests\Orders;

use App\Data\Orders\OrderListData;
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
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'manager_filter' => ['nullable', 'string', 'in:self,all'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function data(): OrderListData
    {
        return OrderListData::from([
            'search' => $this->input('search'),
            'request_status' => $this->input('request_status')
                ? \App\Enums\OrderRequestStatus::from($this->input('request_status'))
                : null,
            'manager_id' => $this->input('manager_id'),
            'manager_filter' => $this->input('manager_filter'),
            'per_page' => $this->input('per_page', 50),
        ]);
    }
}
