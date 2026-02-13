<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnDeliveryResource;
use App\Models\ReturnDelivery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ReturnDeliveryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $returnDeliveries = ReturnDelivery::query()
            ->when($request->string('type')->isNotEmpty(), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('order_id'), fn ($q) => $q->where('order_id', $request->integer('order_id')))
            ->with(['order', 'warehouse'])
            ->paginate($request->integer('per_page', 15));

        return ReturnDeliveryResource::collection($returnDeliveries);
    }

    public function show(ReturnDelivery $returnDelivery): ReturnDeliveryResource
    {
        $returnDelivery->load(['order', 'warehouse', 'processedBy', 'returnDeliveryLines.product']);

        return new ReturnDeliveryResource($returnDelivery);
    }
}
