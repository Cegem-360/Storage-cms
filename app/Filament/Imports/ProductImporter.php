<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Concerns\LocalizedNotifications;
use App\Filament\Imports\Concerns\NormalizesNumericState;
use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;

final class ProductImporter extends Importer
{
    use LocalizedNotifications;
    use NormalizesNumericState;

    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('sku')
                ->label('SKU')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description'),
            ImportColumn::make('barcode')
                ->rules(['max:100']),
            ImportColumn::make('unit_of_measure')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('weight')
                ->numeric()
                ->castStateUsing(fn (mixed $originalState): ?float => self::normalizeFloat($originalState))
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('dimensions'),
            ImportColumn::make('category')
                ->requiredMapping()
                ->relationship(resolveUsing: ['code', 'name'])
                ->helperText(__('Category code or name'))
                ->rules(['required']),
            ImportColumn::make('supplier')
                ->requiredMapping()
                ->relationship(resolveUsing: ['code', 'company_name'])
                ->helperText(__('Supplier code or company name'))
                ->rules(['required']),
            ImportColumn::make('min_stock')
                ->requiredMapping()
                ->numeric()
                ->castStateUsing(fn (mixed $originalState): ?int => self::normalizeInt($originalState))
                ->rules(['required', 'integer']),
            ImportColumn::make('max_stock')
                ->requiredMapping()
                ->numeric()
                ->castStateUsing(fn (mixed $originalState): ?int => self::normalizeInt($originalState))
                ->rules(['required', 'integer']),
            ImportColumn::make('reorder_point')
                ->requiredMapping()
                ->numeric()
                ->castStateUsing(fn (mixed $originalState): ?int => self::normalizeInt($originalState))
                ->rules(['required', 'integer']),
            ImportColumn::make('price')
                ->requiredMapping()
                ->numeric()
                ->castStateUsing(fn (mixed $originalState): ?float => self::normalizeFloat($originalState))
                ->rules(['required', 'numeric']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    public function resolveRecord(): Product
    {
        if ($this->options['updateExisting'] ?? false) {
            return Product::query()->firstOrNew([
                'sku' => $this->data['sku'],
            ]);
        }

        return new Product();
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;
    }
}
