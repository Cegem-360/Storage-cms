<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CountryCode;
use App\Enums\IntrastatDirection;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use DOMDocument;
use SimpleXMLElement;

final class IntrastatXmlExporter
{
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
