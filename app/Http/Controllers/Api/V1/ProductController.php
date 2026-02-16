<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->integer('supplier_id')))
            ->with(['category', 'supplier'])
            ->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->where('team_id', $request->user()->team_id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'array'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'min_stock' => ['sometimes', 'integer', 'min:0'],
            'max_stock' => ['sometimes', 'integer', 'min:0'],
            'reorder_point' => ['sometimes', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'standard_cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'string', 'max:50'],
            'cn_code' => ['nullable', 'string', 'max:8'],
            'country_of_origin' => ['nullable', 'string', 'max:2'],
            'net_weight_kg' => ['nullable', 'numeric', 'min:0'],
            'supplementary_unit' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::query()->create($validated);

        return (new ProductResource($product->load(['category', 'supplier'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'supplier']);

        return new ProductResource($product);
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $validated = $request->validate([
            'sku' => ['sometimes', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product->id)->where('team_id', $request->user()->team_id)],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'unit_of_measure' => ['sometimes', 'string', 'max:50'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'array'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'supplier_id' => ['sometimes', 'integer', 'exists:suppliers,id'],
            'min_stock' => ['sometimes', 'integer', 'min:0'],
            'max_stock' => ['sometimes', 'integer', 'min:0'],
            'reorder_point' => ['sometimes', 'integer', 'min:0'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'standard_cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'string', 'max:50'],
            'cn_code' => ['nullable', 'string', 'max:8'],
            'country_of_origin' => ['nullable', 'string', 'max:2'],
            'net_weight_kg' => ['nullable', 'numeric', 'min:0'],
            'supplementary_unit' => ['nullable', 'string', 'max:255'],
        ]);

        $product->update($validated);

        return new ProductResource($product->load(['category', 'supplier']));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
