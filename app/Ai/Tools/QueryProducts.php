<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Product;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryProducts implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query products in the system. Can search by name, SKU, category, or supplier. Returns product details including stock summary.';
    }

    public function handle(Request $request): string
    {
        $query = Product::query()
            ->where('products.team_id', $this->team->id)
            ->with(['category:id,name', 'supplier:id,company_name', 'stocks:id,product_id,quantity,reserved_quantity']);

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request['category'] ?? null) {
            $query->whereHas('category', function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request['category'].'%');
            });
        }

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        $products = $query->limit(30)->get();

        if ($products->isEmpty()) {
            return 'Nem található termék a megadott szűrőkkel.';
        }

        $result = "Termékek ({$products->count()} tétel):\n\n";

        foreach ($products as $product) {
            $totalStock = (int) $product->stocks->sum('quantity');
            $result .= "- {$product->name} (SKU: {$product->sku}) | "
                ."Kategória: {$product->category?->name} | "
                ."Beszállító: {$product->supplier?->company_name} | "
                .'Ár: '.number_format((float) $product->price, 0, ',', ' ').' Ft | '
                ."Összesen készleten: {$totalStock} {$product->unit_of_measure} | "
                ."Státusz: {$product->status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by product name, SKU, or barcode'),
            'category' => $schema->string()->description('Filter by category name'),
            'status' => $schema->string()->description('Filter by status (active, inactive, discontinued)'),
        ];
    }
}
