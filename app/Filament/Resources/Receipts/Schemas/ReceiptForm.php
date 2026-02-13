<?php

declare(strict_types=1);

namespace App\Filament\Resources\Receipts\Schemas;

use App\Enums\ProductCondition;
use App\Enums\ReceiptStatus;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Receipt;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

final class ReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Receipt Information'))
                    ->schema([
                        TextInput::make('receipt_number')
                            ->label('Receipt Number')
                            ->default(fn (): string => 'REC-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr(bin2hex(random_bytes(3)), 0, 6)))
                            ->required(),
                        Select::make('order_id')
                            ->label('Order')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $order = Order::with('orderLines.product')->find($state);

                                if (! $order) {
                                    return;
                                }

                                $lines = $order->orderLines->map(fn (OrderLine $line): array => [
                                    'product_id' => $line->product_id,
                                    'warehouse_id' => null,
                                    'quantity_expected' => $line->quantity,
                                    'quantity_received' => 0,
                                    'unit_price' => $line->unit_price,
                                    'condition' => ProductCondition::GOOD->value,
                                    'batch_number' => null,
                                    'expiry_date' => null,
                                    'note' => $line->note,
                                ])->toArray();

                                $set('receiptLines', $lines);
                            }),
                        Select::make('warehouse_id')
                            ->label('Warehouse')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('received_by')
                            ->label('Received By')
                            ->relationship('receivedBy', 'first_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('receipt_date')
                            ->label('Receipt Date')
                            ->default(now())
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options(ReceiptStatus::class)
                            ->default(ReceiptStatus::PENDING)
                            ->required(),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Receipt Items'))
                    ->schema([
                        Repeater::make('receiptLines')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(3),

                                Select::make('warehouse_id')
                                    ->label('Warehouse')
                                    ->relationship('warehouse', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),

                                TextInput::make('quantity_expected')
                                    ->label('Expected Qty')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                TextInput::make('quantity_received')
                                    ->label('Received Qty')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                TextEntry::make('variance')
                                    ->label('Variance')
                                    ->state(function (Get $get): string {
                                        $received = (int) ($get('quantity_received') ?? 0);
                                        $expected = (int) ($get('quantity_expected') ?? 0);
                                        $variance = $received - $expected;

                                        return $variance >= 0 ? (string) $variance : (string) $variance;
                                    })
                                    ->columnSpan(1),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('HUF')
                                    ->columnSpan(1),

                                Select::make('condition')
                                    ->label('Condition')
                                    ->options(ProductCondition::class)
                                    ->default(ProductCondition::GOOD)
                                    ->columnSpan(1),

                                DatePicker::make('expiry_date')
                                    ->label('Expiry Date')
                                    ->columnSpan(1),

                                TextInput::make('batch_number')
                                    ->label('Batch Number')
                                    ->columnSpan(1),

                                Textarea::make('note')
                                    ->label('Note')
                                    ->columnSpan(4),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->collapsible(),
                    ]),

                Section::make(__('Summary'))
                    ->schema([
                        TextEntry::make('calculated_total')
                            ->label('Total')
                            ->state(fn (Receipt $record): string => Number::currency(
                                $record?->calculated_total ?? 0,
                                in: 'HUF',
                                locale: 'hu',
                            )),
                    ])
                    ->visibleOn(['edit', 'view']),
            ]);
    }
}
