<?php

namespace App\Http\Requests\Orders;

use App\Data\Orders\OrderReturnData;
use Illuminate\Foundation\Http\FormRequest;

class OrderReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:1000'],
        ];
    }

    public function data(): OrderReturnData
    {
        return OrderReturnData::from([
            'comment' => $this->input('comment'),
        ]);
    }
}
