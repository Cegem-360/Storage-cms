<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Stock;
use App\Models\User;
use App\Notifications\LowStockAlert;
use App\Notifications\OverstockAlert;
use Illuminate\Support\Facades\Notification;

final class StockObserver
{
    public function updated(Stock $stock): void
    {
        if ($stock->wasChanged('quantity')) {
            $this->checkStockLevels($stock);
        }
    }

    public function created(Stock $stock): void
    {
        $this->checkStockLevels($stock);
    }

    private function checkStockLevels(Stock $stock): void
    {
        $isLowStock = $stock->isLowStock() && $stock->quantity > 0;
        $isOverstock = $stock->maximum_stock > 0 && $stock->quantity > $stock->maximum_stock;

        if (! $isLowStock && ! $isOverstock) {
            return;
        }

        $users = User::query()->where('is_super_admin', true)->get();

        if ($isLowStock) {
            Notification::send($users, new LowStockAlert($stock));
        }

        if ($isOverstock) {
            Notification::send($users, new OverstockAlert($stock));
        }
    }
}
