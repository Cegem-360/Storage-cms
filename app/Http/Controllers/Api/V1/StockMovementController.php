<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockMovementResource;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class StockMovementController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $movements = StockMovement::query()
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->with(['product', 'sourceWarehouse', 'targetWarehouse'])
            ->paginate($request->integer('per_page', 15));

        return StockMovementResource::collection($movements);
    }

    public function show(StockMovement $stockMovement): StockMovementResource
    {
        $stockMovement->load(['product', 'sourceWarehouse', 'targetWarehouse', 'batch', 'executor']);

        return new StockMovementResource($stockMovement);
    }
}
