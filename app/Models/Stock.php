<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StockLevel;
use App\Enums\StockStatus;
use App\Models\Concerns\BelongsToTeam;
use App\Observers\StockObserver;
use Exception;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

#[ObservedBy(StockObserver::class)]
final class Stock extends Model
{
    use BelongsToTeam;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'product_id',
        'warehouse_id',
        'warehouse_location_id',
        'quantity',
        'reserved_quantity',
        'minimum_stock',
        'maximum_stock',
        'batch_id',
        'status',
        'unit_cost',
        'total_value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function getAvailableQuantity(): int
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }

    public function reserve(int $quantity): bool
    {
        if ($this->getAvailableQuantity() >= $quantity) {
            $this->increment('reserved_quantity', $quantity);

            return true;
        }

        return false;
    }

    public function release(int $quantity): void
    {
        $this->decrement('reserved_quantity', min($quantity, $this->reserved_quantity));
    }

    public function updateQuantity(int $quantity): void
    {
        $this->update(['quantity' => $quantity]);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }

    public function isOverstock(): bool
    {
        return $this->quantity > $this->maximum_stock;
    }

    public function getStockLevel(): StockLevel
    {
        return match (true) {
            $this->quantity === 0 => StockLevel::OUT_OF_STOCK,
            $this->isLowStock() => StockLevel::LOW_STOCK,
            $this->isOverstock() => StockLevel::OVERSTOCK,
            default => StockLevel::NORMAL,
        };
    }

    #[Override]
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function (self $stock): void {
            $alreadyExists = self::query()->withoutGlobalScopes()
                ->where('product_id', $stock->product_id)
                ->where('warehouse_id', $stock->warehouse_id)
                ->where('team_id', $stock->team_id)
                ->exists();

            if ($alreadyExists) {
                throw new Exception('Stock already exists for this product in this warehouse');
            }
        });
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reserved_quantity' => 'integer',
            'minimum_stock' => 'integer',
            'maximum_stock' => 'integer',
            'status' => StockStatus::class,
            'unit_cost' => 'decimal:4',
            'total_value' => 'decimal:2',
        ];
    }
}
