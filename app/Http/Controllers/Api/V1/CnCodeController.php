<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CnCodeResource;
use App\Models\CnCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CnCodeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $cnCodes = CnCode::query()
            ->when($request->string('search')->isNotEmpty(), fn ($q) => $q->where(function ($q) use ($request): void {
                $q->where('code', 'like', '%'.$request->string('search').'%')
                    ->orWhere('description', 'like', '%'.$request->string('search').'%');
            }))
            ->paginate($request->integer('per_page', 15));

        return CnCodeResource::collection($cnCodes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:8', 'unique:cn_codes,code'],
            'description' => ['required', 'string'],
            'supplementary_unit' => ['nullable', 'string', 'max:50'],
        ]);

        $cnCode = CnCode::query()->create($validated);

        return (new CnCodeResource($cnCode))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CnCode $cnCode): CnCodeResource
    {
        return new CnCodeResource($cnCode);
    }

    public function update(Request $request, CnCode $cnCode): CnCodeResource
    {
        $validated = $request->validate([
            'code' => ['sometimes', 'string', 'size:8', 'unique:cn_codes,code,'.$cnCode->id],
            'description' => ['sometimes', 'string'],
            'supplementary_unit' => ['nullable', 'string', 'max:50'],
        ]);

        $cnCode->update($validated);

        return new CnCodeResource($cnCode);
    }

    public function destroy(CnCode $cnCode): JsonResponse
    {
        $cnCode->delete();

        return response()->json(null, 204);
    }
}
