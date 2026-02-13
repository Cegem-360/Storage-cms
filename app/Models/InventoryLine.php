<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscrepancyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class InventoryLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'system_quantity',
        'actual_quantity',
        'unit_cost',
        'condition',
        'batch_number',
        'expiry_date',
        'note',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function hasVariance(): bool
    {
        return $this->actual_quantity !== $this->system_quantity;
    }

    public function isOverage(): bool
    {
        return $this->actual_quantity > $this->system_quantity;
    }

    public function isShortage(): bool
    {
        return $this->actual_quantity < $this->system_quantity;
    }

    public function getDiscrepancyType(): DiscrepancyType
    {
        return match (true) {
            $this->actual_quantity < $this->system_quantity => DiscrepancyType::SHORTAGE,
            $this->actual_quantity > $this->system_quantity => DiscrepancyType::OVERAGE,
            default => DiscrepancyType::MATCH,
        };
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'system_quantity' => 'integer',
            'actual_quantity' => 'integer',
            'variance_quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'variance_value' => 'float',
            'expiry_date' => 'date',
            'condition' => DiscrepancyType::class,
        ];
    }
}
