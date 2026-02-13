<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReceiptLineResource;
use App\Models\Receipt;
use App\Models\ReceiptLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ReceiptLineController extends Controller
{
    public function index(Request $request, Receipt $receipt): AnonymousResourceCollection
    {
        $lines = $receipt->receiptLines()
            ->with(['product'])
            ->paginate($request->integer('per_page', 15));

        return ReceiptLineResource::collection($lines);
    }

    public function show(Receipt $receipt, ReceiptLine $line): ReceiptLineResource
    {
        $line->load(['product']);

        return new ReceiptLineResource($line);
    }
}
