<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

final class SupplierPrice extends Model
{
    use BelongsToTeam;
    use HasFactory;

    protected $fillable = [
        'team_id',
        'product_id',
        'supplier_id',
        'price',
        'currency',
        'minimum_order_quantity',
        'lead_time_days',
        'valid_from',
        'valid_until',
        'is_active',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function isValidAt(?string $date = null): bool
    {
        $checkDate = $date ? now()->parse($date) : now();

        if ($this->valid_from && $checkDate->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $checkDate->gt($this->valid_until)) {
            return false;
        }

        return $this->is_active;
    }

    public function isCurrentlyValid(): bool
    {
        return $this->isValidAt();
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(SupplierPriceTier::class);
    }

    public function getPriceForQuantity(int $quantity): string
    {
        $tier = $this->tiers
            ->sortByDesc('min_quantity')
            ->first(function (SupplierPriceTier $tier) use ($quantity): bool {
                if ($quantity < $tier->min_quantity) {
                    return false;
                }

                return $tier->max_quantity === null || $quantity <= $tier->max_quantity;
            });

        return $tier?->price ?? $this->price;
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'price' => 'decimal:4',
            'minimum_order_quantity' => 'integer',
            'lead_time_days' => 'integer',
            'valid_from' => 'date',
            'valid_until' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
