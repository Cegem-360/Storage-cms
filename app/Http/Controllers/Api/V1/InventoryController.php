<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class InventoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $inventories = Inventory::query()
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->integer('warehouse_id')))
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->with(['warehouse', 'conductedBy'])
            ->paginate($request->integer('per_page', 15));

        return InventoryResource::collection($inventories);
    }

    public function show(Inventory $inventory): InventoryResource
    {
        $inventory->load(['warehouse', 'conductedBy', 'inventoryLines.product']);

        return new InventoryResource($inventory);
    }
}
