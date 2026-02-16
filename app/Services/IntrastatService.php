<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CountryCode;
use App\Enums\IntrastatDeliveryTerms;
use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Enums\IntrastatTransactionType;
use App\Enums\IntrastatTransportMode;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\StockTransactionType;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockTransaction;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class IntrastatService
{
    public function generateDeclarationForPeriod(
        int $year,
        int $month,
        IntrastatDirection $direction
    ): IntrastatDeclaration {
        return DB::transaction(function () use ($year, $month, $direction): IntrastatDeclaration {
            $declaration = IntrastatDeclaration::query()->create([
                'declaration_number' => $this->generateDeclarationNumber($year, $month, $direction),
                'direction' => $direction,
                'reference_year' => $year,
                'reference_month' => $month,
                'declaration_date' => now(),
                'status' => IntrastatStatus::DRAFT,
            ]);

            $orders = $this->getOrdersForPeriod($year, $month, $direction);

            foreach ($orders as $order) {
                $this->generateLinesFromOrder($declaration, $order, $direction);
            }

            $declaration->calculateTotals();

            return $declaration;
        });
    }

    public function createLinesForDeliveredOrder(Order $order): void
    {
        $order->loadMissing(['orderLines.product', 'supplier', 'customer']);

        if ($order->type === OrderType::PURCHASE && $order->supplier) {
            $this->createInboundLinesForOrder($order);
        } elseif (in_array($order->type, [OrderType::SALES, OrderType::SALE]) && $order->customer) {
            $this->createOutboundLinesForOrder($order);
        }
    }

    public function createLineForInboundTransaction(StockTransaction $stockTransaction): void
    {
        if ($stockTransaction->type !== StockTransactionType::INBOUND) {
            return;
        }

        if ($stockTransaction->reference_type !== Order::class || ! $stockTransaction->reference_id) {
            return;
        }

        $order = Order::query()->find($stockTransaction->reference_id);

        if (! $order?->supplier?->eu_tax_number) {
            return;
        }

        $stockTransaction->loadMissing('product');

        if (! $stockTransaction->product->cn_code) {
            return;
        }

        $declaration = $this->getOrCreateDeclaration(IntrastatDirection::ARRIVAL);
        $supplierCountry = CountryCode::fromAddress($order->supplier->headquarters);

        $this->createIntrastatLine($declaration, [
            'order_id' => $order->id,
            'product_id' => $stockTransaction->product_id,
            'supplier_id' => $order->supplier_id,
            'cn_code' => $stockTransaction->product->cn_code,
            'quantity' => $stockTransaction->quantity,
            'net_mass' => $this->calculateNetMass($stockTransaction->product, $stockTransaction->quantity),
            'invoice_value' => $stockTransaction->total_cost,
            'statistical_value' => $stockTransaction->total_cost,
            'country_of_origin' => $supplierCountry,
            'country_of_consignment' => $supplierCountry ?? CountryCode::HU,
            'country_of_destination' => CountryCode::HU,
            'description' => $stockTransaction->product->name,
        ]);

        $declaration->calculateTotals();
    }

    public function getOrCreateDeclaration(IntrastatDirection $direction, ?DateTimeInterface $date = null): IntrastatDeclaration
    {
        $date ??= now();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('m');

        return IntrastatDeclaration::firstOrCreate(
            [
                'direction' => $direction,
                'reference_year' => $year,
                'reference_month' => $month,
            ],
            [
                'declaration_number' => sprintf('%s-%s-%02d', $direction->value, $year, $month),
                'declaration_date' => $date,
                'status' => IntrastatStatus::DRAFT,
                'team_id' => auth()->user()?->team_id,
            ]
        );
    }

    private function createInboundLinesForOrder(Order $order): void
    {
        if (! $order->supplier->eu_tax_number) {
            return;
        }

        $supplierCountry = CountryCode::fromAddress($order->supplier->headquarters);

        $this->createIntrastatLinesForOrder(
            order: $order,
            direction: IntrastatDirection::ARRIVAL,
            countryOfConsignment: $supplierCountry ?? CountryCode::HU,
            countryOfDestination: CountryCode::HU,
            countryOfOrigin: $supplierCountry,
        );
    }

    private function createOutboundLinesForOrder(Order $order): void
    {
        $customerCountry = CountryCode::fromAddress($order->shipping_address);

        if (! $customerCountry?->isEuMember()) {
            return;
        }

        $this->createIntrastatLinesForOrder(
            order: $order,
            direction: IntrastatDirection::DISPATCH,
            countryOfConsignment: CountryCode::HU,
            countryOfDestination: $customerCountry,
        );
    }

    private function createIntrastatLinesForOrder(
        Order $order,
        IntrastatDirection $direction,
        CountryCode $countryOfConsignment,
        CountryCode $countryOfDestination,
        ?CountryCode $countryOfOrigin = null,
    ): void {
        $declaration = $this->getOrCreateDeclaration($direction, $order->delivery_date);

        foreach ($order->orderLines as $orderLine) {
            if (! $orderLine->product?->cn_code) {
                continue;
            }

            $product = $orderLine->product;
            $lineValue = $orderLine->quantity * $orderLine->unit_price;

            $this->createIntrastatLine($declaration, [
                'order_id' => $order->id,
                'product_id' => $orderLine->product_id,
                'supplier_id' => $order->supplier_id,
                'cn_code' => $product->cn_code,
                'quantity' => $orderLine->quantity,
                'net_mass' => $this->calculateNetMass($product, $orderLine->quantity),
                'invoice_value' => $lineValue,
                'statistical_value' => $lineValue,
                'country_of_origin' => $countryOfOrigin,
                'country_of_consignment' => $countryOfConsignment,
                'country_of_destination' => $countryOfDestination,
                'description' => $product->name,
            ]);
        }

        $declaration->calculateTotals();
    }

    private function generateLinesFromOrder(
        IntrastatDeclaration $declaration,
        Order $order,
        IntrastatDirection $direction
    ): void {
        if (! $this->shouldGenerateLines($order, $direction)) {
            return;
        }

        foreach ($order->orderLines as $orderLine) {
            $product = $orderLine->product;

            if (! $product?->cn_code) {
                continue;
            }

            $this->createIntrastatLine($declaration, [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'supplier_id' => $order->supplier_id,
                'cn_code' => $product->cn_code,
                'quantity' => $orderLine->quantity,
                'net_mass' => ($product->net_weight_kg ?? 0) * $orderLine->quantity,
                'supplementary_unit' => $product->supplementary_unit,
                'supplementary_quantity' => $product->supplementary_unit ? $orderLine->quantity : null,
                'invoice_value' => $orderLine->subtotal,
                'statistical_value' => $orderLine->subtotal,
                'country_of_origin' => $product->country_of_origin ?? CountryCode::HU,
                'country_of_consignment' => $direction === IntrastatDirection::ARRIVAL
                    ? ($order->supplier?->country_code ?? CountryCode::HU)
                    : CountryCode::HU,
                'country_of_destination' => $direction === IntrastatDirection::DISPATCH
                    ? ($order->supplier?->country_code ?? CountryCode::HU)
                    : CountryCode::HU,
                'description' => $product->name,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createIntrastatLine(IntrastatDeclaration $declaration, array $attributes): IntrastatLine
    {
        return IntrastatLine::query()->create(array_merge([
            'intrastat_declaration_id' => $declaration->id,
            'transaction_type' => IntrastatTransactionType::OUTRIGHT_PURCHASE_SALE,
            'transport_mode' => IntrastatTransportMode::ROAD,
            'delivery_terms' => IntrastatDeliveryTerms::EXW,
        ], $attributes));
    }

    private function calculateNetMass(Product $product, float|int $quantity): float
    {
        $weightPerUnit = $product->net_weight_kg ?? $product->weight ?? 0;

        return (float) $weightPerUnit * $quantity;
    }

    private function shouldGenerateLines(Order $order, IntrastatDirection $direction): bool
    {
        if ($direction === IntrastatDirection::ARRIVAL) {
            return $order->supplier
                && $order->supplier->is_eu_member
                && $order->supplier->country_code !== CountryCode::HU;
        }

        return (bool) $order->customer;
    }

    private function generateDeclarationNumber(
        int $year,
        int $month,
        IntrastatDirection $direction
    ): string {
        $dirCode = $direction === IntrastatDirection::ARRIVAL ? 'A' : 'D';

        return sprintf('INTRASTAT-%d%02d-%s', $year, $month, $dirCode);
    }

    private function getOrdersForPeriod(
        int $year,
        int $month,
        IntrastatDirection $direction
    ): Collection {
        $orderType = $direction === IntrastatDirection::ARRIVAL
            ? OrderType::PURCHASE
            : OrderType::SALE;

        return Order::query()
            ->with(['orderLines.product', 'supplier', 'customer'])
            ->where('type', $orderType)
            ->whereIn('status', [OrderStatus::COMPLETED, OrderStatus::CONFIRMED])
            ->whereYear('order_date', $year)
            ->whereMonth('order_date', $month)
            ->get();
    }
}
