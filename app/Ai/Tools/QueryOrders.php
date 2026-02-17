<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Order;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryOrders implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query orders (purchase and sales). Can filter by status, type, supplier/customer name, or date range.';
    }

    public function handle(Request $request): string
    {
        $query = Order::query()
            ->where('orders.team_id', $this->team->id)
            ->with(['supplier:id,company_name', 'customer:id,company_name', 'orderLines:id,order_id,product_id,quantity,subtotal'])
            ->latest('order_date');

        if ($request['status'] ?? null) {
            $query->where('status', $request['status']);
        }

        if ($request['type'] ?? null) {
            $query->where('type', $request['type']);
        }

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', fn ($sq) => $sq->where('company_name', 'like', "%{$search}%"))
                    ->orWhereHas('customer', fn ($cq) => $cq->where('company_name', 'like', "%{$search}%"));
            });
        }

        if ($request['from_date'] ?? null) {
            $query->where('order_date', '>=', $request['from_date']);
        }

        if ($request['to_date'] ?? null) {
            $query->where('order_date', '<=', $request['to_date']);
        }

        $orders = $query->limit(30)->get();

        if ($orders->isEmpty()) {
            return 'Nem található rendelés a megadott szűrőkkel.';
        }

        $result = "Rendelések ({$orders->count()} tétel):\n\n";

        foreach ($orders as $order) {
            $partner = $order->supplier?->company_name ?? $order->customer?->company_name ?? '-';
            $lineCount = $order->orderLines->count();
            $result .= "- #{$order->order_number} | Típus: {$order->type} | "
                ."Partner: {$partner} | "
                ."Dátum: {$order->order_date?->format('Y-m-d')} | "
                ."Szállítás: {$order->delivery_date?->format('Y-m-d')} | "
                .'Összeg: '.number_format((float) $order->total_amount, 0, ',', ' ').' Ft | '
                ."Tételek: {$lineCount} | Státusz: {$order->status}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by order number, supplier, or customer name'),
            'status' => $schema->string()->description('Filter by status (pending, confirmed, shipped, delivered, cancelled)'),
            'type' => $schema->string()->description('Filter by type (purchase, sales)'),
            'from_date' => $schema->string()->description('Filter orders from this date (YYYY-MM-DD)'),
            'to_date' => $schema->string()->description('Filter orders until this date (YYYY-MM-DD)'),
        ];
    }
}
