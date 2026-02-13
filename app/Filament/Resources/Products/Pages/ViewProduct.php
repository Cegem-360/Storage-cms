<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Services\BarcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Override;
use Picqer\Barcode\BarcodeGeneratorPNG;

final class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            Action::make('generateBarcode')
                ->label('Generate Barcode')
                ->icon(Heroicon::OutlinedQrCode)
                ->color('gray')
                ->visible(fn (Product $record): bool => empty($record->barcode))
                ->requiresConfirmation()
                ->action(function (Product $record): void {
                    $record->update(['barcode' => BarcodeService::generateEan13()]);

                    Notification::make()
                        ->title(__('Barcode generated successfully'))
                        ->success()
                        ->send();
                }),

            Action::make('printLabel')
                ->label('Print Label')
                ->icon(Heroicon::OutlinedPrinter)
                ->color('info')
                ->visible(fn (Product $record): bool => ! empty($record->barcode))
                ->action(function (Product $record) {
                    $generator = new BarcodeGeneratorPNG();
                    $barcodeImage = base64_encode(
                        $generator->getBarcode($record->barcode, BarcodeGeneratorPNG::TYPE_EAN_13)
                    );

                    $pdf = Pdf::loadView('pdf.product-label', [
                        'product' => $record,
                        'barcodeImage' => $barcodeImage,
                    ])->setPaper([0, 0, 226.77, 141.73]); // ~80mm x 50mm

                    return response()->streamDownload(
                        fn () => print ($pdf->output()),
                        "label-{$record->sku}.pdf",
                    );
                }),
        ];
    }
}
