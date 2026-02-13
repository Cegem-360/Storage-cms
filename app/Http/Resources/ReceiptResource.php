<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'receipt_number' => $this->receipt_number,
            'status' => $this->status,
            'received_at' => $this->received_at,
            'notes' => $this->notes,
            'order' => new OrderResource($this->whenLoaded('order')),
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'receivedBy' => new EmployeeResource($this->whenLoaded('receivedBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
