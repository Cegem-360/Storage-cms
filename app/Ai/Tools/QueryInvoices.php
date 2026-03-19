<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Invoice;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryInvoices implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query invoices (számlák). Can search by invoice number. Filter by status. Shows invoice details including customer, dates, and amounts.';
    }

    public function handle(Request $request): string
    {
        $query = Invoice::query()
            ->where('invoices.team_id', $this->team->id)
            ->with([
                'customer:id,name,customer_code',
                'supplier:id,company_name',
                'order:id,order_number',
            ]);

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn ($sq) => $sq->where('company_name', 'like', "%{$search}%"));
            });
        }

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        $invoices = $query->latest('invoice_date')->limit(30)->get();

        if ($invoices->isEmpty()) {
            return 'Nem található számla a megadott szűrőkkel.';
        }

        $result = "Számlák ({$invoices->count()} tétel):\n\n";

        foreach ($invoices as $invoice) {
            $partner = $invoice->customer?->name ?? $invoice->supplier?->company_name ?? '-';
            $total = number_format((float) $invoice->total_amount, 0, ',', ' ');
            $result .= "- #{$invoice->invoice_number} | "
                ."Partner: {$partner} | "
                ."Rendelés: {$invoice->order?->order_number} | "
                ."Dátum: {$invoice->invoice_date?->format('Y-m-d')} | "
                ."Fizetési határidő: {$invoice->due_date?->format('Y-m-d')} | "
                ."Összeg: {$total} Ft | "
                ."Pénznem: {$invoice->currency} | "
                ."Státusz: {$invoice->status?->value}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by invoice number, customer name, or supplier name'),
            'status' => $schema->string()->description('Filter by status (draft, issued, paid, overdue, cancelled)'),
        ];
    }
}
