<?php

namespace App\Http\Requests\Archive;

use App\Data\Archive\ArchiveListData;
use Illuminate\Foundation\Http\FormRequest;

class ArchiveListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'dateFrom' => ['nullable', 'date_format:Y-m-d'],
            'dateTo' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    public function data(): ArchiveListData
    {
        return ArchiveListData::from([
            'search' => $this->input('search'),
            'manager_id' => $this->input('manager_id') ? (int) $this->input('manager_id') : null,
            'date_from' => $this->input('dateFrom'),
            'date_to' => $this->input('dateTo'),
        ]);
    }
}
