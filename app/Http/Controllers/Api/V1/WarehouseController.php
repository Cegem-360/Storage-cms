<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class WarehouseController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $warehouses = Warehouse::query()
            ->when($request->string('type')->isNotEmpty(), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->with(['manager'])
            ->paginate($request->integer('per_page', 15));

        return WarehouseResource::collection($warehouses);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:warehouses,code'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'capacity' => ['sometimes', 'integer', 'min:0'],
            'manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'is_active' => ['sometimes', 'boolean'],
            'valuation_method' => ['nullable', 'string', 'max:255'],
            'is_consignment' => ['sometimes', 'boolean'],
            'owner_supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
        ]);

        $warehouse = Warehouse::query()->create($validated);

        return (new WarehouseResource($warehouse->load(['manager', 'ownerSupplier'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Warehouse $warehouse): WarehouseResource
    {
        $warehouse->load(['manager', 'ownerSupplier']);

        return new WarehouseResource($warehouse);
    }

    public function update(Request $request, Warehouse $warehouse): WarehouseResource
    {
        $validated = $request->validate([
            'code' => ['sometimes', 'string', 'max:255', 'unique:warehouses,code,'.$warehouse->id],
            'name' => ['sometimes', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'max:255'],
            'capacity' => ['sometimes', 'integer', 'min:0'],
            'manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'is_active' => ['sometimes', 'boolean'],
            'valuation_method' => ['nullable', 'string', 'max:255'],
            'is_consignment' => ['sometimes', 'boolean'],
            'owner_supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
        ]);

        $warehouse->update($validated);

        return new WarehouseResource($warehouse->load(['manager', 'ownerSupplier']));
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $warehouse->delete();

        return response()->json(null, 204);
    }
}
