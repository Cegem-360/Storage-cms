<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

#[ObservedBy(OrderObserver::class)]
final class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'type',
        'customer_id',
        'supplier_id',
        'status',
        'order_date',
        'delivery_date',
        'total_amount',
        'shipping_address',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderLines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function addLine(OrderLine $line): void
    {
        $this->orderLines()->save($line);
        $this->refreshTotal();
    }

    public function removeLine(OrderLine $line): void
    {
        $line->delete();
        $this->refreshTotal();
    }

    public function refreshTotal(): void
    {
        $this->update(['total_amount' => $this->calculated_total]);
    }

    public function process(): void
    {
        $this->update(['status' => OrderStatus::PROCESSING]);
    }

    public function cancel(): void
    {
        $this->update(['status' => OrderStatus::CANCELLED]);
    }

    public function getTrackingNumber(): string
    {
        return $this->order_number;
    }

    protected function calculatedTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) $this->orderLines->sum(fn (OrderLine $line): float => $line->subtotal),
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'delivery_date' => 'date',
            'total_amount' => 'decimal:2',
            'shipping_address' => 'array',
            'status' => OrderStatus::class,
            'type' => OrderType::class,
        ];
    }
}
