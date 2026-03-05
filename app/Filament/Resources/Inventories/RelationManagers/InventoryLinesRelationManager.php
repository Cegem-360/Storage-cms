<?php

declare(strict_types=1);

namespace App\Filament\Resources\Inventories\RelationManagers;

use App\Enums\DiscrepancyType;
use App\Models\Product;
use App\Models\Team;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Override;

final class InventoryLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryLines';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('product_data')
                    ->schema([
                        Select::make('product_id')
                            ->label(__('Product'))
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set): void {
                                if ($state) {
                                    $product = Product::query()
                                        ->with('stocks')
                                        ->find($state);

                                    if ($product && $this->getOwnerRecord()->warehouse_id) {
                                        $stock = $product->stocks()
                                            ->where('warehouse_id', $this->getOwnerRecord()->warehouse_id)
                                            ->first();

                                        if ($stock) {
                                            $set('system_quantity', $stock->quantity);
                                        }
                                    }
                                }
                            }),

                        Group::make()
                            ->schema([
                                TextInput::make('batch_number')
                                    ->maxLength(255),

                                DatePicker::make('expiry_date'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('quantity_data')
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('system_quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('actual_quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function ($state, Get $get, Set $set): void {
                                        $systemQuantity = $get('system_quantity') ?? 0;
                                        $actualQuantity = $get('actual_quantity') ?? 0;
                                        $unitCost = $state ?? 0;

                                        $variance = ($actualQuantity - $systemQuantity) * $unitCost;
                                        $set('variance_value', $variance);
                                    }),

                                TextInput::make('unit_cost')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->prefix(Team::currency())
                                    ->live()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set): void {
                                        $systemQuantity = $get('system_quantity') ?? 0;
                                        $actualQuantity = $get('actual_quantity') ?? 0;
                                        $unitCost = $state ?? 0;

                                        $variance = ($actualQuantity - $systemQuantity) * $unitCost;
                                        $set('variance_value', $variance);
                                    }),
                            ])
                            ->columns(3),

                        TextInput::make('variance_value')
                            ->afterStateHydrated(function ($state, $get, $set): void {
                                $systemQuantity = $get('system_quantity') ?? 0;
                                $actualQuantity = $get('actual_quantity') ?? 0;
                                $unitCost = $get('unit_cost') ?? 0;

                                $variance = ($actualQuantity - $systemQuantity) * $unitCost;
                                $set('variance_value', $variance);
                            })
                            ->numeric()
                            ->disabled()
                            ->prefix(Team::currency())
                            ->dehydrated(false),
                    ]),

                Section::make('status_notes')
                    ->label(__('Status & Notes'))
                    ->schema([
                        Select::make('condition')
                            ->options(DiscrepancyType::class)
                            ->enum(DiscrepancyType::class),

                        Textarea::make('note')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('batch_number')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('system_quantity')
                    ->label(__('System Qty'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('actual_quantity')
                    ->label(__('Actual Qty'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('variance_quantity')
                    ->label(__('Variance'))
                    ->numeric()
                    ->badge()
                    ->color(fn (Model $record): string => match ($record->getDiscrepancyType()) {
                        DiscrepancyType::SHORTAGE => 'danger',
                        DiscrepancyType::OVERAGE => 'warning',
                        DiscrepancyType::MATCH => 'success',
                    })
                    ->sortable(),

                TextColumn::make('unit_cost')
                    ->money(Team::currency())
                    ->sortable(),

                TextColumn::make('variance_value')
                    ->money(Team::currency())
                    ->sortable()
                    ->color(fn (Model $record): string => $record->hasVariance() ? 'danger' : 'success'),

                TextColumn::make('condition')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['system_quantity'] = $data['system_quantity'] ?? 0;
                        $data['actual_quantity'] = $data['actual_quantity'] ?? 0;

                        return $data;
                    })
                    ->after(function (): void {
                        $this->getOwnerRecord()->calculateVariance();
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function (): void {
                        $this->getOwnerRecord()->calculateVariance();
                    }),
                DeleteAction::make()
                    ->after(function (): void {
                        $this->getOwnerRecord()->calculateVariance();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function (): void {
                            $this->getOwnerRecord()->calculateVariance();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
