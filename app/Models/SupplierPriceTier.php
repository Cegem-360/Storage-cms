<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class SupplierPriceTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_price_id',
        'min_quantity',
        'max_quantity',
        'price',
    ];

    public function supplierPrice(): BelongsTo
    {
        return $this->belongsTo(SupplierPrice::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'min_quantity' => 'integer',
            'max_quantity' => 'integer',
            'price' => 'decimal:4',
        ];
    }
}
