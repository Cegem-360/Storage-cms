<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InventoryStatus;
use App\Enums\InventoryType;
use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

final class Inventory extends Model
{
    use BelongsToTeam;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'inventory_number',
        'warehouse_id',
        'conducted_by',
        'inventory_date',
        'status',
        'type',
        'notes',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function conductedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'conducted_by');
    }

    public function inventoryLines(): HasMany
    {
        return $this->hasMany(InventoryLine::class);
    }

    public function addLine(InventoryLine $line): void
    {
        $this->inventoryLines()->save($line);
        $this->refreshVarianceData();
    }

    public function removeLine(InventoryLine $line): void
    {
        $line->delete();
        $this->refreshVarianceData();
    }

    /**
     * Refresh the in-memory inventory lines so virtual variance columns are up to date.
     */
    public function refreshVarianceData(): void
    {
        $this->load('inventoryLines');
    }

    /**
     * @deprecated Use refreshVarianceData() instead.
     */
    public function calculateVariance(): void
    {
        $this->refreshVarianceData();
    }

    public function complete(): void
    {
        $this->update(['status' => InventoryStatus::COMPLETED]);
    }

    public function approve(): void
    {
        $this->update(['status' => InventoryStatus::APPROVED]);
    }

    public function applyCorrections(): void
    {
        DB::transaction(function (): void {
            foreach ($this->inventoryLines as $line) {
                if ($line->hasVariance()) {
                    $stock = Stock::query()->firstOrCreate(
                        [
                            'product_id' => $line->product_id,
                            'warehouse_id' => $this->warehouse_id,
                        ],
                        [
                            'team_id' => $this->team_id,
                            'quantity' => 0,
                            'reserved_quantity' => 0,
                            'minimum_stock' => 0,
                            'maximum_stock' => 1000,
                        ]
                    );

                    $stock->update(['quantity' => $line->actual_quantity]);
                }
            }

            $this->update(['status' => InventoryStatus::APPROVED]);
        });
    }

    protected function casts(): array
    {
        return [
            'status' => InventoryStatus::class,
            'type' => InventoryType::class,
            'inventory_date' => 'date',
        ];
    }
}
