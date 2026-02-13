<?php

declare(strict_types=1);

use App\Enums\IntrastatDeliveryTerms;
use App\Enums\IntrastatTransactionType;
use App\Enums\IntrastatTransportMode;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Services\IntrastatXmlExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->exporter = new IntrastatXmlExporter();
});

it('exports declaration to XML successfully', function (): void {
    $declaration = IntrastatDeclaration::factory()
        ->arrival()
        ->withTotals(100000, 100000, 150.5)
        ->create();

    IntrastatLine::factory()
        ->forArrival()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
            'cn_code' => '12345678',
            'quantity' => 10,
            'net_mass' => 15.5,
            'invoice_value' => 100000,
            'statistical_value' => 100000,
            'transaction_type' => IntrastatTransactionType::OUTRIGHT_PURCHASE_SALE,
            'transport_mode' => IntrastatTransportMode::ROAD,
            'delivery_terms' => IntrastatDeliveryTerms::EXW,
        ]);

    $xml = $this->exporter->exportToXml($declaration);

    expect($xml)->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->and($xml)->toContain('<INTRASTAT>')
        ->and($xml)->toContain('<HEADER>')
        ->and($xml)->toContain('<ITEMS>')
        ->and($xml)->toContain('<SUMMARY>')
        ->and($xml)->toContain('<CN_CODE>12345678</CN_CODE>')
        ->and($xml)->toContain('<FLOW_CODE>A</FLOW_CODE>');
});

it('exports XML with correct flow code for dispatch', function (): void {
    $declaration = IntrastatDeclaration::factory()
        ->dispatch()
        ->create();

    IntrastatLine::factory()
        ->forDispatch()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
        ]);

    $xml = $this->exporter->exportToXml($declaration);

    expect($xml)->toContain('<FLOW_CODE>D</FLOW_CODE>');
});

it('includes supplementary unit in XML if provided', function (): void {
    $declaration = IntrastatDeclaration::factory()->create();

    IntrastatLine::factory()
        ->withSupplementaryUnit()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
            'supplementary_unit' => 'p/st',
            'supplementary_quantity' => 100,
        ]);

    $xml = $this->exporter->exportToXml($declaration);

    expect($xml)->toContain('<SUPPLEMENTARY_UNIT>p/st</SUPPLEMENTARY_UNIT>')
        ->and($xml)->toContain('<SUPPLEMENTARY_QUANTITY>100.00</SUPPLEMENTARY_QUANTITY>');
});

it('includes country of origin for arrivals in XML', function (): void {
    $declaration = IntrastatDeclaration::factory()
        ->arrival()
        ->create();

    IntrastatLine::factory()
        ->forArrival()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
            'country_of_origin' => 'CN',
        ]);

    $xml = $this->exporter->exportToXml($declaration);

    expect($xml)->toContain('<COUNTRY_OF_ORIGIN>CN</COUNTRY_OF_ORIGIN>');
});

it('does not include country of origin for dispatches in XML', function (): void {
    $declaration = IntrastatDeclaration::factory()
        ->dispatch()
        ->create();

    IntrastatLine::factory()
        ->forDispatch()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
            'country_of_origin' => 'HU',
        ]);

    $xml = $this->exporter->exportToXml($declaration);

    expect($xml)->not->toContain('<COUNTRY_OF_ORIGIN>');
});

it('exports declaration to iFORM XML for KSH-Elektra submission', function (): void {
    $declaration = IntrastatDeclaration::factory()
        ->dispatch()
        ->withTotals(360000, 360000, 20.0)
        ->create([
            'reference_year' => 2025,
            'reference_month' => 10,
        ]);

    IntrastatLine::factory()
        ->forDispatch()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
            'cn_code' => '84821010',
            'quantity' => 10,
            'net_mass' => 20.0,
            'invoice_value' => 360000,
            'statistical_value' => 360000,
            'country_of_destination' => 'AT',
            'transaction_type' => IntrastatTransactionType::OUTRIGHT_PURCHASE_SALE,
            'transport_mode' => IntrastatTransportMode::ROAD,
            'delivery_terms' => IntrastatDeliveryTerms::FOB,
        ]);

    $xml = $this->exporter->exportToIFormXml($declaration);

    expect($xml)->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->and($xml)->toContain('<form xmlns="http://iform-html.kdiv.hu/schemas/form">')
        ->and($xml)->toContain('<keys>')
        ->and($xml)->toContain('<name>iformVersion</name>')
        ->and($xml)->toContain('<templateKeys>')
        ->and($xml)->toContain('<name>OSAP</name>')
        ->and($xml)->toContain('<value>2010</value>')
        ->and($xml)->toContain('<chapter s="P">')
        ->and($xml)->toContain('<table name="Termek">')
        ->and($xml)->toContain('<identifier>TEKOD</identifier>')
        ->and($xml)->toContain('<value>84821010</value>')
        ->and($xml)->toContain('<identifier>SZAORSZ</identifier>')
        ->and($xml)->toContain('<value>AT</value>')
        ->and($xml)->toContain('<identifier>KGM</identifier>')
        ->and($xml)->toContain('<value>20.000</value>');
});

it('exports arrival declaration to iFORM XML with correct OSAP code', function (): void {
    $declaration = IntrastatDeclaration::factory()
        ->arrival()
        ->withTotals(100000, 100000, 15.5)
        ->create([
            'reference_year' => 2025,
            'reference_month' => 10,
        ]);

    IntrastatLine::factory()
        ->forArrival()
        ->create([
            'intrastat_declaration_id' => $declaration->id,
            'cn_code' => '87088099',
            'quantity' => 5,
            'net_mass' => 15.5,
            'invoice_value' => 100000,
            'statistical_value' => 100000,
            'country_of_consignment' => 'DE',
            'country_of_origin' => 'DE',
            'transaction_type' => IntrastatTransactionType::OUTRIGHT_PURCHASE_SALE,
            'transport_mode' => IntrastatTransportMode::ROAD,
            'delivery_terms' => IntrastatDeliveryTerms::EXW,
        ]);

    $xml = $this->exporter->exportToIFormXml($declaration);

    expect($xml)->toContain('<value>2012</value>')
        ->and($xml)->toContain('<identifier>FTA</identifier>')
        ->and($xml)->toContain('<identifier>STAERT</identifier>')
        ->and($xml)->toContain('<identifier>SZSZAORSZ</identifier>');
});
