<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Customer;
use App\Models\Team;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

final readonly class QueryCustomers implements Tool
{
    public function __construct(private Team $team) {}

    public function description(): string
    {
        return 'Query customers (vevők). Can search by name, customer code, or email. Shows customer details including credit limit and balance.';
    }

    public function handle(Request $request): string
    {
        $query = Customer::query()
            ->where('customers.team_id', $this->team->id)
            ->withCount('orders');

        if ($request['search'] ?? null) {
            $search = $request['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->limit(30)->get();

        if ($customers->isEmpty()) {
            return 'Nem található vevő a megadott szűrőkkel.';
        }

        $result = "Vevők ({$customers->count()} tétel):\n\n";

        foreach ($customers as $customer) {
            $creditLimit = number_format((float) $customer->credit_limit, 0, ',', ' ');
            $balance = number_format((float) $customer->balance, 0, ',', ' ');
            $creditStatus = $customer->isOverCreditLimit() ? ' [HITELTÚLLÉPÉS]' : '';
            $result .= "- {$customer->name} (Kód: {$customer->customer_code}) | "
                ."Email: {$customer->email} | "
                ."Telefon: {$customer->phone} | "
                ."Típus: {$customer->type?->value} | "
                ."Hitelkeret: {$creditLimit} Ft | "
                ."Egyenleg: {$balance} Ft{$creditStatus} | "
                ."Rendelések: {$customer->orders_count}\n";
        }

        return $result;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('Search by customer name, customer code, or email'),
        ];
    }
}
