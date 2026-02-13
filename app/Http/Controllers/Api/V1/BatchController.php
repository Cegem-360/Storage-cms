<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class BatchController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $batches = Batch::query()
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->integer('supplier_id')))
            ->when($request->string('quality_status')->isNotEmpty(), fn ($q) => $q->where('quality_status', $request->string('quality_status')))
            ->with(['product', 'supplier'])
            ->paginate($request->integer('per_page', 15));

        return BatchResource::collection($batches);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_number' => ['required', 'string', 'max:255', 'unique:batches,batch_number'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'manufacture_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:manufacture_date'],
            'serial_numbers' => ['nullable', 'array'],
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'quality_status' => ['nullable', 'string', 'max:255'],
        ]);

        $batch = Batch::query()->create($validated);

        return (new BatchResource($batch->load(['product', 'supplier'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Batch $batch): BatchResource
    {
        $batch->load(['product', 'supplier']);

        return new BatchResource($batch);
    }

    public function update(Request $request, Batch $batch): BatchResource
    {
        $validated = $request->validate([
            'batch_number' => ['sometimes', 'string', 'max:255', 'unique:batches,batch_number,'.$batch->id],
            'product_id' => ['sometimes', 'integer', 'exists:products,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'manufacture_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'serial_numbers' => ['nullable', 'array'],
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'quality_status' => ['nullable', 'string', 'max:255'],
        ]);

        $batch->update($validated);

        return new BatchResource($batch->load(['product', 'supplier']));
    }

    public function destroy(Batch $batch): JsonResponse
    {
        $batch->delete();

        return response()->json(null, 204);
    }
}
