<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryLineResource;
use App\Models\Inventory;
use App\Models\InventoryLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class InventoryLineController extends Controller
{
    public function index(Request $request, Inventory $inventory): AnonymousResourceCollection
    {
        $lines = $inventory->inventoryLines()
            ->with(['product'])
            ->paginate($request->integer('per_page', 15));

        return InventoryLineResource::collection($lines);
    }

    public function show(Inventory $inventory, InventoryLine $line): InventoryLineResource
    {
        $line->load(['product']);

        return new InventoryLineResource($line);
    }
}
