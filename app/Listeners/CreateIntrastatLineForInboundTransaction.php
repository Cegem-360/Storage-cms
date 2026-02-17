<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\InboundStockReceived;
use App\Services\IntrastatService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final readonly class CreateIntrastatLineForInboundTransaction implements ShouldHandleEventsAfterCommit
{
    public function __construct(private IntrastatService $intrastatService) {}

    public function handle(InboundStockReceived $event): void
    {
        $this->intrastatService->createLineForInboundTransaction($event->stockTransaction);
    }
}
