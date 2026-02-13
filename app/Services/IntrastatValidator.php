<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CountryCode;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;

final class IntrastatValidator
{
    /** @return array<int, string> */
    public function validateDeclaration(IntrastatDeclaration $declaration): array
    {
        $errors = [];

        if ($declaration->intrastatLines()->count() === 0) {
            $errors[] = 'Declaration must have at least one line';
        }

        foreach ($declaration->intrastatLines as $index => $line) {
            $errors = array_merge($errors, $this->validateLine($line, $index + 1));
        }

        return $errors;
    }

    /** @return array<int, string> */
    private function validateLine(IntrastatLine $line, int $lineNumber): array
    {
        $errors = [];
        $prefix = "Sor {$lineNumber}: ";

        if (! $line->cn_code || mb_strlen($line->cn_code) !== 8 || ! ctype_digit($line->cn_code)) {
            $errors[] = $prefix.'KN kód kötelező, pontosan 8 számjegyből kell állnia';
        }

        if (! $line->net_mass || $line->net_mass < 0.001) {
            $errors[] = $prefix.'Nettó tömeg kötelező, minimum 0.001 kg';
        }

        if (! $line->invoice_value || $line->invoice_value < 1) {
            $errors[] = $prefix.'Számlaérték kötelező, minimum 1 HUF';
        }

        if (! $line->statistical_value || $line->statistical_value < 1) {
            $errors[] = $prefix.'Statisztikai érték kötelező, minimum 1 HUF';
        }

        if ($line->country_of_consignment && (! $line->country_of_consignment->isEuMember() || $line->country_of_consignment === CountryCode::HU)) {
            $errors[] = $prefix.'Feladás országa érvénytelen (csak EU tagállamok, HU kivételével)';
        }

        if ($line->country_of_destination && (! $line->country_of_destination->isEuMember() || $line->country_of_destination === CountryCode::HU)) {
            $errors[] = $prefix.'Rendeltetési ország érvénytelen (csak EU tagállamok, HU kivételével)';
        }

        if (! $line->transaction_type) {
            $errors[] = $prefix.'Ügylet jellege kötelező';
        }

        if (! $line->transport_mode) {
            $errors[] = $prefix.'Szállítási mód kötelező';
        }

        if (! $line->delivery_terms) {
            $errors[] = $prefix.'Szállítási feltétel kötelező (KSH követelmény)';
        }

        if (! $line->quantity || $line->quantity <= 0) {
            $errors[] = $prefix.'Mennyiség kötelező és pozitív kell legyen';
        }

        return $errors;
    }
}
