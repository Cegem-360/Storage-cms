<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierPriceResource;
use App\Models\SupplierPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class SupplierPriceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $supplierPrices = SupplierPrice::query()
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->integer('supplier_id')))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->with(['product', 'supplier'])
            ->paginate($request->integer('per_page', 15));

        return SupplierPriceResource::collection($supplierPrices);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'minimum_order_quantity' => ['nullable', 'integer', 'min:1'],
            'lead_time_days' => ['nullable', 'integer', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $supplierPrice = SupplierPrice::query()->create($validated);

        return (new SupplierPriceResource($supplierPrice->load(['product', 'supplier'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(SupplierPrice $supplierPrice): SupplierPriceResource
    {
        $supplierPrice->load(['product', 'supplier']);

        return new SupplierPriceResource($supplierPrice);
    }

    public function update(Request $request, SupplierPrice $supplierPrice): SupplierPriceResource
    {
        $validated = $request->validate([
            'product_id' => ['sometimes', 'integer', 'exists:products,id'],
            'supplier_id' => ['sometimes', 'integer', 'exists:suppliers,id'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'minimum_order_quantity' => ['nullable', 'integer', 'min:1'],
            'lead_time_days' => ['nullable', 'integer', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $supplierPrice->update($validated);

        return new SupplierPriceResource($supplierPrice->load(['product', 'supplier']));
    }

    public function destroy(SupplierPrice $supplierPrice): JsonResponse
    {
        $supplierPrice->delete();

        return response()->json(null, 204);
    }
}
