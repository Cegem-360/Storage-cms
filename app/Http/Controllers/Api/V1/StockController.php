<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class StockController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $stocks = Stock::query()
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->integer('warehouse_id')))
            ->with(['product', 'warehouse', 'batch'])
            ->paginate($request->integer('per_page', 15));

        return StockResource::collection($stocks);
    }

    public function show(Stock $stock): StockResource
    {
        $stock->load(['product', 'warehouse', 'batch']);

        return new StockResource($stock);
    }
}
