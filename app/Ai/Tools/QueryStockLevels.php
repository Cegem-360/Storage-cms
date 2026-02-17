<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Stock;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryStockLevels implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query current stock levels across warehouses. Can filter by warehouse name, product name/SKU, or show only low stock items.';
    }

    public function handle(Request $request): string
    {
        $query = Stock::query()
            ->where('stocks.team_id', $this->team->id)
            ->with(['product:id,name,sku,unit_of_measure', 'warehouse:id,name,code']);

        if ($request['warehouse'] ?? null) {
            $query->whereHas('warehouse', function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request['warehouse'].'%');
            });
        }

        if ($request['product'] ?? null) {
            $query->whereHas('product', function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request['product'].'%')
                    ->orWhere('sku', 'like', '%'.$request['product'].'%');
            });
        }

        if ($request['low_stock_only'] ?? false) {
            $query->whereColumn('quantity', '<', 'minimum_stock');
        }

        $stocks = $query->limit(50)->get();

        if ($stocks->isEmpty()) {
            return 'Nem található készletadat a megadott szűrőkkel.';
        }

        $result = "Készletadatok ({$stocks->count()} tétel):\n\n";

        foreach ($stocks as $stock) {
            $status = $stock->quantity < $stock->minimum_stock ? '[ALACSONY]' : '[OK]';
            $available = $stock->quantity - $stock->reserved_quantity;
            $result .= "- {$stock->product?->name} ({$stock->product?->sku}) | "
                ."Raktár: {$stock->warehouse?->name} | "
                ."Mennyiség: {$stock->quantity} | Elérhető: {$available} | "
                ."Min: {$stock->minimum_stock} {$status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'warehouse' => $schema->string()->description('Filter by warehouse name'),
            'product' => $schema->string()->description('Filter by product name or SKU'),
            'low_stock_only' => $schema->boolean()->description('Only show items below minimum stock level'),
        ];
    }
}
