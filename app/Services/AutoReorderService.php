<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Team;

final class AutoReorderService
{
    public function createDraftOrder(Product $product, Team $team): ?Order
    {
        if (! $product->supplier_id) {
            return null;
        }

        if ($this->hasOpenPurchaseOrder($product)) {
            return null;
        }

        $quantity = $product->calculateReorderQuantity();

        if ($quantity <= 0) {
            return null;
        }

        $order = Order::query()->create([
            'team_id' => $team->id,
            'order_number' => 'PO-'.now()->format('Ymd').'-'.mb_str_pad((string) (Order::withoutGlobalScopes()->count() + 1), 4, '0', STR_PAD_LEFT),
            'type' => OrderType::PURCHASE,
            'supplier_id' => $product->supplier_id,
            'status' => OrderStatus::DRAFT,
            'order_date' => now(),
            'total_amount' => 0,
        ]);

        $order->orderLines()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $product->standard_cost ?? $product->price ?? 0,
            'discount_percent' => 0,
        ]);

        $order->refreshTotal();

        return $order;
    }

    private function hasOpenPurchaseOrder(Product $product): bool
    {
        return Order::query()
            ->where('type', OrderType::PURCHASE)
            ->whereIn('status', [
                OrderStatus::DRAFT,
                OrderStatus::CONFIRMED,
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED,
            ])
            ->whereHas('orderLines', fn ($query) => $query->where('product_id', $product->id))
            ->exists();
    }
}
