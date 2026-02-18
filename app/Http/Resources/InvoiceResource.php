<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class InvoiceResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'order_id' => $this->order_id,
            'receipt_id' => $this->receipt_id,
            'supplier_id' => $this->supplier_id,
            'customer_id' => $this->customer_id,
            'issued_by' => $this->issued_by,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'tax_total' => $this->tax_total,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
            'order' => new OrderResource($this->whenLoaded('order')),
            'receipt' => new ReceiptResource($this->whenLoaded('receipt')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'issuedBy' => new EmployeeResource($this->whenLoaded('issuedBy')),
            'invoiceLines' => InvoiceLineResource::collection($this->whenLoaded('invoiceLines')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
