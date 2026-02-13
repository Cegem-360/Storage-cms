<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\StockTransaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final readonly class InboundStockReceived
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public StockTransaction $stockTransaction) {}
}
