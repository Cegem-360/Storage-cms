<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'company_name' => $this->company_name,
            'trade_name' => $this->trade_name,
            'headquarters' => $this->headquarters,
            'mailing_address' => $this->mailing_address,
            'country_code' => $this->country_code,
            'is_eu_member' => $this->is_eu_member,
            'tax_number' => $this->tax_number,
            'eu_tax_number' => $this->eu_tax_number,
            'company_registration_number' => $this->company_registration_number,
            'bank_account_number' => $this->bank_account_number,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'rating' => $this->rating,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
