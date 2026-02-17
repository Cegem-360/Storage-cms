<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\StockTransactionType;
use App\Events\InboundStockReceived;
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

        event(new InboundStockReceived($stockTransaction));
    }
}
