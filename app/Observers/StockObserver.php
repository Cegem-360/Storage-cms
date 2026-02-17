<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Stock;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LowStockAlert;
use App\Notifications\OverstockAlert;
use App\Notifications\ReorderPointReached;
use App\Services\AutoReorderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\Notification;

final readonly class StockObserver
{
    public function __construct(private AutoReorderService $autoReorderService) {}

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
        $team = Team::query()->with('settings')->find($stock->team_id);

        if (! $team) {
            return;
        }

        $isLowStock = $stock->isLowStock() && $stock->quantity > 0;
        $isOverstock = $stock->isOverstock();

        if ($isLowStock || $isOverstock) {
            $this->sendStockAlerts($stock, $team, $isLowStock, $isOverstock);
        }

        $this->checkReorderPoint($stock, $team);
    }

    private function sendStockAlerts(Stock $stock, Team $team, bool $isLowStock, bool $isOverstock): void
    {
        $users = $this->getNotifiableUsers($stock->team_id);
        $notificationEmail = $team->getSetting('notification_email');

        if ($isLowStock) {
            $this->sendNotification($users, new LowStockAlert($stock), $notificationEmail);
        }

        if ($isOverstock) {
            $this->sendNotification($users, new OverstockAlert($stock), $notificationEmail);
        }
    }

    private function checkReorderPoint(Stock $stock, Team $team): void
    {
        $product = $stock->product;

        if (! $product || ! $product->needsReorder()) {
            return;
        }

        $product->loadMissing('supplier');

        if ($product->supplier) {
            $users = $this->getNotifiableUsers($stock->team_id);
            $notificationEmail = $team->getSetting('notification_email');

            $this->sendNotification($users, new ReorderPointReached($product), $notificationEmail);
        }

        if ((bool) $team->getSetting('auto_reorder_enabled', false)) {
            $this->autoReorderService->createDraftOrder($product, $team);
        }
    }

    /**
     * @return Collection<int, User>
     */
    private function getNotifiableUsers(int $teamId): Collection
    {
        return User::query()
            ->where('team_id', $teamId)
            ->orWhere('is_super_admin', true)
            ->get();
    }

    private function sendNotification(
        Collection $users,
        BaseNotification $notification,
        ?string $notificationEmail,
    ): void {
        Notification::send($users, $notification);

        if ($notificationEmail) {
            Notification::route('mail', $notificationEmail)
                ->notify(clone $notification);
        }
    }
}
