<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Services\IntrastatService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final readonly class CreateIntrastatLinesForDeliveredOrder implements ShouldHandleEventsAfterCommit
{
    public function __construct(private IntrastatService $intrastatService) {}

    public function handle(OrderDelivered $event): void
    {
        $this->intrastatService->createLinesForDeliveredOrder($event->order);
    }
}
