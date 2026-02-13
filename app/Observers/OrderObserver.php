<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\IntrastatDeliveryTerms;
use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Enums\IntrastatTransactionType;
use App\Enums\IntrastatTransportMode;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Models\Order;
use DateTimeInterface;

final class OrderObserver
{
    public function updated(Order $order): void
    {
        // Only create Intrastat lines when order is completed/delivered
        if ($order->isDirty('status') && $order->status === OrderStatus::DELIVERED) {
            $this->createIntrastatLines($order);
        }
    }

    private function createIntrastatLines(Order $order): void
    {
        // Only process orders with EU suppliers/customers
        if ($order->type === OrderType::PURCHASE && $order->supplier) {
            $this->createInboundIntrastatLines($order);
        } elseif ($order->type === OrderType::SALES && $order->customer) {
            $this->createOutboundIntrastatLines($order);
        }
    }

    private function createInboundIntrastatLines(Order $order): void
    {
        if (! $order->supplier->eu_tax_number) {
            return;
        }

        $supplierCountry = $this->extractCountryCode($order->supplier->headquarters);

        $this->createIntrastatLinesForOrder(
            order: $order,
            direction: IntrastatDirection::ARRIVAL,
            countryOfConsignment: $supplierCountry ?? 'HU',
            countryOfDestination: 'HU',
            countryOfOrigin: $supplierCountry,
        );
    }

    private function createOutboundIntrastatLines(Order $order): void
    {
        $customerCountry = $this->extractCountryCode($order->shipping_address);

        if (! $customerCountry || ! $this->isEuCountry($customerCountry)) {
            return;
        }

        $this->createIntrastatLinesForOrder(
            order: $order,
            direction: IntrastatDirection::DISPATCH,
            countryOfConsignment: 'HU',
            countryOfDestination: $customerCountry,
        );
    }

    private function createIntrastatLinesForOrder(
        Order $order,
        IntrastatDirection $direction,
        string $countryOfConsignment,
        string $countryOfDestination,
        ?string $countryOfOrigin = null,
    ): void {
        $declaration = $this->getOrCreateDeclaration($direction, $order->delivery_date);

        foreach ($order->orderLines as $orderLine) {
            if (! $orderLine->product?->cn_code) {
                continue;
            }

            $lineValue = $orderLine->quantity * $orderLine->unit_price;

            IntrastatLine::create([
                'intrastat_declaration_id' => $declaration->id,
                'order_id' => $order->id,
                'product_id' => $orderLine->product_id,
                'supplier_id' => $order->supplier_id,
                'cn_code' => $orderLine->product->cn_code,
                'quantity' => $orderLine->quantity,
                'net_mass' => ($orderLine->product->weight ?? 0) * $orderLine->quantity,
                'invoice_value' => $lineValue,
                'statistical_value' => $lineValue,
                'country_of_origin' => $countryOfOrigin,
                'country_of_consignment' => $countryOfConsignment,
                'country_of_destination' => $countryOfDestination,
                'transaction_type' => IntrastatTransactionType::OUTRIGHT_PURCHASE_SALE,
                'transport_mode' => IntrastatTransportMode::ROAD,
                'delivery_terms' => IntrastatDeliveryTerms::EXW,
                'description' => $orderLine->product->name,
            ]);
        }

        $declaration->calculateTotals();
    }

    private function getOrCreateDeclaration(IntrastatDirection $direction, ?DateTimeInterface $date): IntrastatDeclaration
    {
        $date = $date ?? now();
        $year = $date->format('Y');
        $month = $date->format('m');

        return IntrastatDeclaration::firstOrCreate(
            [
                'direction' => $direction,
                'reference_year' => $year,
                'reference_month' => $month,
            ],
            [
                'declaration_number' => sprintf('%s-%s-%s', $direction->value, $year, $month),
                'declaration_date' => now(),
                'status' => IntrastatStatus::DRAFT,
            ]
        );
    }

    private function extractCountryCode(array|string|null $address): ?string
    {
        if (is_array($address) && isset($address['country'])) {
            return $address['country'];
        }

        return null;
    }

    private function isEuCountry(string $countryCode): bool
    {
        $euCountries = [
            'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR',
            'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
            'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE',
        ];

        return in_array(mb_strtoupper($countryCode), $euCountries, true);
    }
}
