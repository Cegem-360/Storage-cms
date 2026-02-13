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
use DOMDocument;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

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
            ]
        );
    }

    /**
     * Export declaration to KSH iFORM-compliant XML format for KSH-Elektra submission.
     */
    public function exportToIFormXml(IntrastatDeclaration $declaration): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><form xmlns="http://iform-html.kdiv.hu/schemas/form"></form>');
        $isArrival = $declaration->direction === IntrastatDirection::ARRIVAL;

        $keys = $xml->addChild('keys');
        $this->addKey($keys, 'iformVersion', '1.13.7');

        $templateKeys = $xml->addChild('templateKeys');
        $this->addKey($templateKeys, 'OSAP', $isArrival ? '2012' : '2010');
        $this->addKey($templateKeys, 'EV', (string) $declaration->reference_year);
        $this->addKey($templateKeys, 'HO', (string) $declaration->reference_month);
        $this->addKey($templateKeys, 'VARIANT', '1');
        $this->addKey($templateKeys, 'MUTATION', '0');

        $metadataChapter = $xml->addChild('chapter');
        $metadataChapter->addAttribute('s', 'P');
        $this->addData($metadataChapter, 'MHO', sprintf('%02d', $declaration->reference_month));
        $this->addData($metadataChapter, 'MEV', (string) $declaration->reference_year);
        $this->addData($metadataChapter, 'ADOSZAM', config('app.tax_number', '12345678-2-42'));

        $lineItemsChapter = $xml->addChild('chapter');
        $lineItemsChapter->addAttribute('s', 'P');
        $this->addData($lineItemsChapter, 'LAP_SUM', (string) $declaration->intrastatLines->count());
        $this->addData($lineItemsChapter, 'LAP_KGM_SUM', number_format((float) $declaration->total_net_mass, 3, '.', ''));

        $table = $lineItemsChapter->addChild('table');
        $table->addAttribute('name', 'Termek');

        foreach ($declaration->intrastatLines as $index => $line) {
            $row = $table->addChild('row');

            $this->addData($row, 'T_SORSZ', (string) ($index + 1));
            $this->addData($row, 'TEKOD', $line->cn_code);

            $transactionField = $isArrival ? 'FTA' : 'RTA';
            $this->addData($row, $transactionField, $line->transaction_type->value);

            $countryCode = $this->resolveCountryCode($line, $declaration->direction);
            if ($countryCode) {
                $this->addData($row, 'SZAORSZ', $countryCode->value);
            }

            $this->addData($row, 'KGM', number_format((float) $line->net_mass, 3, '.', ''));

            $valueField = $isArrival ? 'STAERT' : 'SZAOSSZ';
            $this->addData($row, $valueField, (string) (int) $line->statistical_value);

            if ($line->supplementary_quantity) {
                $this->addData($row, 'KIEGME', number_format((float) $line->supplementary_quantity, 2, '.', ''));
                $this->addData($row, 'UKOD', '11');
            }

            $this->addData($row, 'SZALMOD', $line->transport_mode->value);
            $this->addData($row, 'SZALFEL', $line->delivery_terms->value);

            if ($isArrival && $line->country_of_origin) {
                $this->addData($row, 'SZSZAORSZ', $line->country_of_origin->value);
            }
        }

        return $this->formatXml($xml);
    }

    /**
     * Export declaration to simplified XML format for documentation and internal use.
     */
    public function exportToXml(IntrastatDeclaration $declaration): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><INTRASTAT></INTRASTAT>');
        $isArrival = $declaration->direction === IntrastatDirection::ARRIVAL;

        $header = $xml->addChild('HEADER');
        $header->addChild('PSI_ID', config('app.tax_number', '12345678-2-42'));
        $header->addChild('REFERENCE_PERIOD', sprintf(
            '%d%02d',
            $declaration->reference_year,
            $declaration->reference_month
        ));
        $header->addChild('FLOW_CODE', $isArrival ? 'A' : 'D');
        $header->addChild('DECLARATION_DATE', $declaration->declaration_date->format('Y-m-d'));
        $header->addChild('CURRENCY_CODE', 'HUF');

        $items = $xml->addChild('ITEMS');
        $lineNumber = 1;

        foreach ($declaration->intrastatLines as $line) {
            $item = $items->addChild('ITEM');
            $item->addChild('LINE_NUMBER', (string) $lineNumber++);
            $item->addChild('CN_CODE', $line->cn_code);

            $countryCode = $this->resolveCountryCode($line, $declaration->direction);
            if ($countryCode) {
                $item->addChild('COUNTRY_CODE', $countryCode->value);
            }

            $item->addChild('NATURE_OF_TRANSACTION', $line->transaction_type->value);
            $item->addChild('MODE_OF_TRANSPORT', $line->transport_mode->value);
            $item->addChild('DELIVERY_TERMS', $line->delivery_terms->value);
            $item->addChild('STATISTICAL_VALUE', (string) (int) $line->statistical_value);
            $item->addChild('NET_MASS', number_format((float) $line->net_mass, 3, '.', ''));

            if ($line->supplementary_unit && $line->supplementary_quantity) {
                $item->addChild('SUPPLEMENTARY_UNIT', $line->supplementary_unit);
                $item->addChild('SUPPLEMENTARY_QUANTITY', number_format((float) $line->supplementary_quantity, 2, '.', ''));
            }

            if ($isArrival && $line->country_of_origin) {
                $item->addChild('COUNTRY_OF_ORIGIN', $line->country_of_origin->value);
            }
        }

        $summary = $xml->addChild('SUMMARY');
        $summary->addChild('TOTAL_LINES', (string) $declaration->intrastatLines->count());
        $summary->addChild('TOTAL_STATISTICAL_VALUE', (string) (int) $declaration->total_statistical_value);
        $summary->addChild('TOTAL_NET_MASS', number_format((float) $declaration->total_net_mass, 3, '.', ''));

        return $this->formatXml($xml);
    }

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
     * Create an IntrastatLine with default transaction attributes merged with the given overrides.
     *
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

    private function resolveCountryCode(IntrastatLine $line, IntrastatDirection $direction): ?CountryCode
    {
        return $direction === IntrastatDirection::ARRIVAL
            ? $line->country_of_consignment
            : $line->country_of_destination;
    }

    private function formatXml(SimpleXMLElement $xml): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML();
    }

    private function addKey(SimpleXMLElement $parent, string $name, string $value): void
    {
        $key = $parent->addChild('key');
        $key->addChild('name', $name);
        $key->addChild('value', $value);
    }

    private function addData(SimpleXMLElement $parent, string $identifier, string $value): void
    {
        $data = $parent->addChild('data');
        $data->addAttribute('s', 'P');
        $data->addChild('identifier', $identifier);
        $data->addChild('value', $value);
    }
}
