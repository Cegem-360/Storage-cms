<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockTransactionResource;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class StockTransactionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $transactions = StockTransaction::query()
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->integer('warehouse_id')))
            ->when($request->string('type')->isNotEmpty(), fn ($q) => $q->where('type', $request->string('type')))
            ->with(['product', 'warehouse'])
            ->paginate($request->integer('per_page', 15));

        return StockTransactionResource::collection($transactions);
    }

    public function show(StockTransaction $stockTransaction): StockTransactionResource
    {
        $stockTransaction->load(['product', 'warehouse', 'stock']);

        return new StockTransactionResource($stockTransaction);
    }
}
