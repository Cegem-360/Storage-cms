<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class InvoiceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $invoices = Invoice::query()
            ->when($request->filled('order_id'), fn ($q) => $q->where('order_id', $request->integer('order_id')))
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->with(['order', 'customer', 'supplier'])
            ->paginate($request->integer('per_page', 15));

        return InvoiceResource::collection($invoices);
    }

    public function show(Invoice $invoice): InvoiceResource
    {
        $invoice->load(['order', 'receipt', 'customer', 'supplier', 'issuedBy', 'invoiceLines.product']);

        return new InvoiceResource($invoice);
    }
}
