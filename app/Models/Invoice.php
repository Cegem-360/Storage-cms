<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

final class Invoice extends Model
{
    use BelongsToTeam;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'invoice_number',
        'order_id',
        'receipt_id',
        'supplier_id',
        'customer_id',
        'issued_by',
        'invoice_date',
        'due_date',
        'status',
        'subtotal',
        'tax_total',
        'total_amount',
        'currency',
        'payment_method',
        'notes',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issued_by');
    }

    public function invoiceLines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function refreshTotal(): void
    {
        $this->update([
            'subtotal' => $this->calculated_subtotal,
            'tax_total' => $this->calculated_tax_total,
            'total_amount' => $this->calculated_total,
        ]);
    }

    protected function calculatedSubtotal(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) $this->invoiceLines->sum(fn (InvoiceLine $line): float => (float) $line->subtotal),
        );
    }

    protected function calculatedTaxTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) $this->invoiceLines->sum(fn (InvoiceLine $line): float => (float) $line->tax_amount),
        );
    }

    protected function calculatedTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) $this->invoiceLines->sum(fn (InvoiceLine $line): float => (float) $line->line_total),
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'status' => InvoiceStatus::class,
        ];
    }
}
