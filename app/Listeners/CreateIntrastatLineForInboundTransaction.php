<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\InboundStockReceived;
use App\Services\IntrastatService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final class CreateIntrastatLineForInboundTransaction implements ShouldHandleEventsAfterCommit
{
    public function __construct(private readonly IntrastatService $intrastatService) {}

    public function handle(InboundStockReceived $event): void
    {
        $this->intrastatService->createLineForInboundTransaction($event->stockTransaction);
    }
}
