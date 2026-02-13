<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'cn_code' => $this->cn_code,
            'country_of_origin' => $this->country_of_origin,
            'net_weight_kg' => $this->net_weight_kg,
            'supplementary_unit' => $this->supplementary_unit,
            'name' => $this->name,
            'description' => $this->description,
            'barcode' => $this->barcode,
            'unit_of_measure' => $this->unit_of_measure,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'reorder_point' => $this->reorder_point,
            'price' => $this->price,
            'standard_cost' => $this->standard_cost,
            'status' => $this->status,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
