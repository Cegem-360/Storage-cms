<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReceiptStatus;
use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

final class Receipt extends Model
{
    use BelongsToTeam;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'receipt_number',
        'order_id',
        'warehouse_id',
        'received_by',
        'receipt_date',
        'status',
        'total_amount',
        'notes',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'received_by');
    }

    public function receiptLines(): HasMany
    {
        return $this->hasMany(ReceiptLine::class);
    }

    public function addLine(ReceiptLine $line): void
    {
        $this->receiptLines()->save($line);
        $this->refreshTotal();
    }

    public function removeLine(ReceiptLine $line): void
    {
        $line->delete();
        $this->refreshTotal();
    }

    public function refreshTotal(): void
    {
        $this->update(['total_amount' => $this->calculated_total]);
    }

    public function confirm(): void
    {
        $this->update(['status' => ReceiptStatus::CONFIRMED]);
    }

    public function reject(): void
    {
        $this->update(['status' => ReceiptStatus::REJECTED]);
    }

    protected function calculatedTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) $this->receiptLines->sum(fn (ReceiptLine $line): float => $line->line_total),
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'receipt_date' => 'date',
            'total_amount' => 'decimal:2',
            'status' => ReceiptStatus::class,
        ];
    }
}
