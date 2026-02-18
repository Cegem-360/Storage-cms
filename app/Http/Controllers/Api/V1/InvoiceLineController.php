<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceLineResource;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class InvoiceLineController extends Controller
{
    public function index(Request $request, Invoice $invoice): AnonymousResourceCollection
    {
        $lines = $invoice->invoiceLines()
            ->with(['product'])
            ->paginate($request->integer('per_page', 15));

        return InvoiceLineResource::collection($lines);
    }

    public function show(Invoice $invoice, InvoiceLine $line): InvoiceLineResource
    {
        $line->load(['product']);

        return new InvoiceLineResource($line);
    }
}
