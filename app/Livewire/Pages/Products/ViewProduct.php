<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products;

use App\Filament\Resources\Products\Schemas\ProductInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Product;
use App\Services\BarcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Picqer\Barcode\BarcodeGeneratorPNG;

#[Layout('components.layouts.dashboard')]
final class ViewProduct extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function generateBarcodeAction(): Action
    {
        return Action::make('generateBarcode')
            ->label(__('Generate Barcode'))
            ->icon(Heroicon::OutlinedQrCode)
            ->color('gray')
            ->visible(fn (): bool => empty($this->record->barcode))
            ->requiresConfirmation()
            ->action(function (): void {
                $this->record->update(['barcode' => BarcodeService::generateEan13()]);

                Notification::make()
                    ->title(__('Barcode generated successfully'))
                    ->success()
                    ->send();
            });
    }

    public function printLabelAction(): Action
    {
        return Action::make('printLabel')
            ->label(__('Print Label'))
            ->icon(Heroicon::OutlinedPrinter)
            ->color('info')
            ->visible(fn (): bool => ! empty($this->record->barcode))
            ->action(function () {
                $generator = new BarcodeGeneratorPNG();
                $barcodeImage = base64_encode(
                    $generator->getBarcode($this->record->barcode, BarcodeGeneratorPNG::TYPE_EAN_13)
                );

                $pdf = Pdf::loadView('pdf.product-label', [
                    'product' => $this->record,
                    'barcodeImage' => $barcodeImage,
                ])->setPaper([0, 0, 226.77, 141.73]);

                return response()->streamDownload(
                    fn () => print ($pdf->output()),
                    "label-{$this->record->sku}.pdf",
                );
            });
    }

    public function render(): View
    {
        return view('livewire.pages.products.view-product');
    }

    protected static function getModel(): string
    {
        return Product::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.products';
    }

    protected static function getResourceLabel(): string
    {
        return 'Product';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return ProductInfolist::class;
    }

    /** @return Builder<Product> */
    protected function getRecordQuery(): Builder
    {
        return Product::query()->with(['category', 'supplier', 'stocks.warehouse']);
    }
}
