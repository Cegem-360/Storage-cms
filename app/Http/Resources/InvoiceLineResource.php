<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class InvoiceLineResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'discount_percent' => $this->discount_percent,
            'tax_percent' => $this->tax_percent,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'line_total' => $this->line_total,
            'note' => $this->note,
            'created_at' => $this->created_at,
        ];
    }
}
