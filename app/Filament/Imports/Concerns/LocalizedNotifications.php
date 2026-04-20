<?php

declare(strict_types=1);

namespace App\Filament\Imports\Concerns;

use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Number;

trait LocalizedNotifications
{
    public static function getCompletedNotificationTitle(Import $import): string
    {
        App::setLocale(config('app.locale', 'hu'));

        return parent::getCompletedNotificationTitle($import);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        App::setLocale(config('app.locale', 'hu'));

        $body = __('Import completed, :count row(s) imported.', [
            'count' => Number::format($import->successful_rows),
        ]);

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.__(':count row(s) failed to import.', [
                'count' => Number::format($failedRowsCount),
            ]);
        }

        return $body;
    }
}
