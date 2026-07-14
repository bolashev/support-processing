<?php

namespace App\Http\Resources;

use App\Models\OrderDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderDocument
 */
class OrderDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'name' => $this->name,
            'file_path' => $this->file_path,
            'external_url' => $this->external_url,
        ];
    }
}
