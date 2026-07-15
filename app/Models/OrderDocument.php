<?php

namespace App\Models;

use App\Enums\OrderDocumentType;
use App\Models\Traits\SerializeDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property int $id
 * @property int $order_id
 * @property OrderDocumentType $type
 * @property string $name
 * @property string|null $file_path
 * @property string|null $external_url
 * @property int|null $bitrix_file_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Order $order
 */
class OrderDocument extends Model
{
    use HasFactory, SerializeDate;

    protected $guarded = ['_token', '_method'];

    protected function casts(): array
    {
        return [
            'type' => OrderDocumentType::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
