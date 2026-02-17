<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Receipt;
use App\Models\Team;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class BillingoService
{
    private const string BASE_URL = 'https://api.billingo.hu/v3';

    /**
     * @return array{success: bool, message: string, invoice_id?: int}
     */
    public function createInvoiceFromReceipt(Receipt $receipt, Team $team): array
    {
        $apiKey = $team->getSetting('billingo_api_key');
        $blockId = $team->getSetting('billingo_block_id');

        if (! $apiKey || ! $blockId) {
            return [
                'success' => false,
                'message' => __('Billingo API key or Block ID is not configured.'),
            ];
        }

        $receipt->load(['receiptLines.product', 'order.supplier']);

        $partnerId = $this->findOrCreatePartner($apiKey, $receipt);

        if (! $partnerId) {
            return [
                'success' => false,
                'message' => __('Could not create Billingo partner.'),
            ];
        }

        $items = $receipt->receiptLines->map(fn ($line) => [
            'name' => $line->product?->name ?? __('Product'),
            'unit_price' => (float) $line->unit_price,
            'unit_price_type' => 'gross',
            'quantity' => (float) $line->quantity_received,
            'unit' => __('pcs'),
            'vat' => '27%',
            'comment' => $line->notes ?? '',
        ])->toArray();

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
            ])->post(self::BASE_URL.'/documents', [
                'partner_id' => $partnerId,
                'block_id' => (int) $blockId,
                'type' => 'invoice',
                'fulfillment_date' => $receipt->receipt_date->format('Y-m-d'),
                'due_date' => now()->addDays(30)->format('Y-m-d'),
                'payment_method' => 'wire_transfer',
                'language' => 'hu',
                'currency' => 'HUF',
                'electronic' => false,
                'items' => $items,
                'comment' => __('Receipt').': '.$receipt->receipt_number,
            ]);

            if ($response->successful()) {
                $invoiceId = $response->json('id');

                return [
                    'success' => true,
                    'message' => __('Billingo invoice created successfully.').' #'.$response->json('invoice_number', $invoiceId),
                    'invoice_id' => $invoiceId,
                ];
            }

            Log::error('Billingo API error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'receipt_id' => $receipt->id,
            ]);

            return [
                'success' => false,
                'message' => __('Billingo API error: :error', ['error' => $response->json('message', $response->body())]),
            ];
        } catch (ConnectionException $e) {
            Log::error('Billingo connection error', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => __('Could not connect to Billingo. Please try again later.'),
            ];
        }
    }

    private function findOrCreatePartner(string $apiKey, Receipt $receipt): ?int
    {
        $supplier = $receipt->order?->supplier;

        if (! $supplier) {
            return null;
        }

        try {
            $searchResponse = Http::withHeaders([
                'X-API-KEY' => $apiKey,
            ])->get(self::BASE_URL.'/partners', [
                'query' => $supplier->tax_number ?? $supplier->company_name,
            ]);

            if ($searchResponse->successful()) {
                $partners = $searchResponse->json('data', []);
                if (! empty($partners)) {
                    return $partners[0]['id'];
                }
            }

            $createResponse = Http::withHeaders([
                'X-API-KEY' => $apiKey,
            ])->post(self::BASE_URL.'/partners', [
                'name' => $supplier->company_name,
                'address' => [
                    'country_code' => 'HU',
                    'post_code' => $supplier->postal_code ?? '',
                    'city' => $supplier->city ?? '',
                    'address' => $supplier->street ?? '',
                ],
                'emails' => array_filter([$supplier->contact_email]),
                'taxcode' => $supplier->tax_number ?? '',
                'phone' => $supplier->contact_phone ?? '',
            ]);

            if ($createResponse->successful()) {
                return $createResponse->json('id');
            }

            Log::error('Billingo partner creation failed', ['body' => $createResponse->body()]);

            return null;
        } catch (ConnectionException $e) {
            Log::error('Billingo partner search/create error', ['error' => $e->getMessage()]);

            return null;
        }
    }
}
