<?php

namespace App\Http\Requests\Notes;

use App\Data\Notes\NoteStoreData;
use Illuminate\Foundation\Http\FormRequest;

class NoteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:5000'],
        ];
    }

    public function data(): NoteStoreData
    {
        return NoteStoreData::from([
            'body' => $this->input('body'),
        ]);
    }
}
