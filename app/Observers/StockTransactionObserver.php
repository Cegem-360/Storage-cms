<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\IntrastatDeliveryTerms;
use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Enums\IntrastatTransactionType;
use App\Enums\IntrastatTransportMode;
use App\Enums\StockTransactionType;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Models\Order;
use App\Models\StockTransaction;

final class StockTransactionObserver
{
    public function created(StockTransaction $stockTransaction): void
    {
        if ($stockTransaction->type !== StockTransactionType::INBOUND) {
            return;
        }

        if ($stockTransaction->reference_type !== Order::class || ! $stockTransaction->reference_id) {
            return;
        }

        /** @var Order|null $order */
        $order = Order::query()->find($stockTransaction->reference_id);

        if (! $order?->supplier?->eu_tax_number) {
            return;
        }

        $this->createInboundIntrastatLine($stockTransaction, $order);
    }

    private function createInboundIntrastatLine(StockTransaction $stockTransaction, Order $order): void
    {
        if (! $stockTransaction->product->cn_code) {
            return;
        }

        $declaration = $this->getOrCreateDeclaration(IntrastatDirection::ARRIVAL);

        IntrastatLine::create([
            'intrastat_declaration_id' => $declaration->id,
            'order_id' => $order->id,
            'product_id' => $stockTransaction->product_id,
            'supplier_id' => $order->supplier_id,
            'cn_code' => $stockTransaction->product->cn_code,
            'quantity' => $stockTransaction->quantity,
            'net_mass' => ($stockTransaction->product->weight ?? 0) * $stockTransaction->quantity,
            'invoice_value' => $stockTransaction->total_cost,
            'statistical_value' => $stockTransaction->total_cost,
            'country_of_origin' => $this->extractCountryCode($order->supplier->headquarters),
            'country_of_consignment' => $this->extractCountryCode($order->supplier->headquarters) ?? 'HU',
            'country_of_destination' => 'HU',
            'transaction_type' => IntrastatTransactionType::OUTRIGHT_PURCHASE_SALE,
            'transport_mode' => IntrastatTransportMode::ROAD,
            'delivery_terms' => IntrastatDeliveryTerms::EXW,
            'description' => $stockTransaction->product->name,
        ]);

        $declaration->calculateTotals();
    }

    private function getOrCreateDeclaration(IntrastatDirection $direction): IntrastatDeclaration
    {
        $year = now()->format('Y');
        $month = now()->format('m');

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
}
