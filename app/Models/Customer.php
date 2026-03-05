<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CustomerType;
use App\Enums\InvoiceStatus;
use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

final class Customer extends Model
{
    use BelongsToTeam;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'customer_code',
        'name',
        'email',
        'phone',
        'billing_address',
        'shipping_address',
        'credit_limit',
        'balance',
        'type',
    ];

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Helper methods
    public function getOutstandingBalance(): float
    {
        return (float) $this->invoices()
            ->whereNotIn('status', [InvoiceStatus::PAID, InvoiceStatus::CANCELLED])
            ->sum('total_amount');
    }

    public function refreshBalance(): void
    {
        $this->update(['balance' => $this->getOutstandingBalance()]);
    }

    public function checkCreditLimit(float $amount = 0): bool
    {
        if ((float) $this->credit_limit <= 0) {
            return true;
        }

        return ($this->balance + $amount) <= (float) $this->credit_limit;
    }

    public function isOverCreditLimit(): bool
    {
        return ! $this->checkCreditLimit();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'billing_address' => 'array',
            'shipping_address' => 'array',
            'credit_limit' => 'decimal:2',
            'balance' => 'decimal:2',
            'type' => CustomerType::class,
        ];
    }
}
