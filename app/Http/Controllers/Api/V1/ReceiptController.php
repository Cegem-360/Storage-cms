<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReceiptResource;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ReceiptController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $receipts = Receipt::query()
            ->when($request->filled('order_id'), fn ($q) => $q->where('order_id', $request->integer('order_id')))
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->integer('warehouse_id')))
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->with(['order', 'warehouse'])
            ->paginate($request->integer('per_page', 15));

        return ReceiptResource::collection($receipts);
    }

    public function show(Receipt $receipt): ReceiptResource
    {
        $receipt->load(['order', 'warehouse', 'receivedBy', 'receiptLines.product']);

        return new ReceiptResource($receipt);
    }
}
