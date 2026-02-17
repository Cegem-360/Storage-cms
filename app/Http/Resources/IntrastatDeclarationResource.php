<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class IntrastatDeclarationResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'declaration_number' => $this->declaration_number,
            'direction' => $this->direction,
            'reference_year' => $this->reference_year,
            'reference_month' => $this->reference_month,
            'status' => $this->status,
            'total_invoice_value' => $this->total_invoice_value,
            'total_statistical_value' => $this->total_statistical_value,
            'total_net_mass' => $this->total_net_mass,
            'submitted_at' => $this->submitted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
