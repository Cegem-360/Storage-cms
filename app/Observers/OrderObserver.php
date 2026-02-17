<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Events\OrderDelivered;
use App\Models\Order;

final class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === OrderStatus::DELIVERED) {
            event(new OrderDelivered($order));
        }
    }
}
