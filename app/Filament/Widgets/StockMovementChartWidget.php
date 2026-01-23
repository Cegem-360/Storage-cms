<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

final class StockMovementChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Stock Movements (Last 30 Days)');
    }

    protected function getData(): array
    {
        $inboundData = [];
        $outboundData = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            $inboundData[] = StockMovement::whereDate('created_at', $date)
                ->where('quantity', '>', 0)
                ->sum('quantity');

            $outboundData[] = abs(StockMovement::whereDate('created_at', $date)
                ->where('quantity', '<', 0)
                ->sum('quantity'));
        }

        return [
            'datasets' => [
                [
                    'label' => __('Inbound'),
                    'data' => $inboundData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'fill' => true,
                ],
                [
                    'label' => __('Outbound'),
                    'data' => $outboundData,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
