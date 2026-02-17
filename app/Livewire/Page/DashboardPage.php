<?php

declare(strict_types=1);

namespace App\Livewire\Page;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('Dashboard')]
final class DashboardPage extends Component
{
    #[Computed]
    public function totalProducts(): int
    {
        return Product::query()->count();
    }

    #[Computed]
    public function totalStock(): int
    {
        return (int) Stock::query()->sum('quantity');
    }

    #[Computed]
    public function lowStockCount(): int
    {
        return (int) Stock::query()->whereColumn('quantity', '<', 'minimum_stock')->count();
    }

    #[Computed]
    public function pendingOrders(): int
    {
        return (int) Order::query()->whereIn('status', [OrderStatus::DRAFT, OrderStatus::CONFIRMED, OrderStatus::PROCESSING])->count();
    }

    #[Computed]
    public function lowStockProducts()
    {
        return Stock::with(['product', 'warehouse'])
            ->whereColumn('quantity', '<', 'minimum_stock')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentOrders()
    {
        return Order::with('supplier')
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function chartData(): array
    {
        $inboundData = [];
        $outboundData = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Date::now()->subDays($i);
            $labels[] = $date->format('M d');

            $inboundData[] = (int) StockMovement::query()->whereDate('created_at', $date)
                ->where('quantity', '>', 0)
                ->sum('quantity');

            $outboundData[] = abs((int) StockMovement::query()->whereDate('created_at', $date)
                ->where('quantity', '<', 0)
                ->sum('quantity'));
        }

        return [
            'labels' => $labels,
            'inbound' => $inboundData,
            'outbound' => $outboundData,
        ];
    }

    public function render(): Factory|View
    {
        return view('livewire.page.dashboard-page');
    }
}
