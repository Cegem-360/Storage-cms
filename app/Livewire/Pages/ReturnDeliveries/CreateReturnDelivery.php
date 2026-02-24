<?php

declare(strict_types=1);

namespace App\Livewire\Pages\ReturnDeliveries;

use App\Enums\ProductCondition;
use App\Enums\ReturnReason;
use App\Enums\ReturnStatus;
use App\Enums\ReturnType;
use App\Models\Order;
use App\Models\ReturnDelivery;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateReturnDelivery extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->model(ReturnDelivery::class)
            ->operation('create')
            ->components([
                Wizard::make([
                    Step::make(__('Return Information'))
                        ->description(__('Basic return details and type'))
                        ->schema([
                            TextInput::make('return_number')
                                ->label(__('Return Number'))
                                ->default(fn (): string => 'RET-'.mb_strtoupper(uniqid()))
                                ->required()
                                ->maxLength(100)
                                ->scopedUnique(ignoreRecord: true),

                            Select::make('type')
                                ->label(__('Return Type'))
                                ->options(ReturnType::class)
                                ->enum(ReturnType::class)
                                ->required()
                                ->live()
                                ->default(ReturnType::CUSTOMER_RETURN),

                            Select::make('warehouse_id')
                                ->relationship('warehouse', 'name')
                                ->label(__('Warehouse'))
                                ->searchable()
                                ->preload()
                                ->required(),

                            DatePicker::make('return_date')
                                ->label(__('Return Date'))
                                ->default(now())
                                ->required(),
                        ])
                        ->columns(2),

                    Step::make(__('Related Records'))
                        ->description(__('Customer, supplier, or order information'))
                        ->schema([
                            Select::make('order_id')
                                ->relationship('order', 'order_number', modifyQueryUsing: function ($query): void {
                                    $query->where('status', '!=', 'cancelled');
                                })
                                ->label(__('Related Order'))
                                ->afterStateUpdated(function (Set $set, int $state): void {
                                    $order = Order::query()->find($state);
                                    $set('customer_id', $order?->customer_id);
                                    $set('supplier_id', $order?->supplier_id);
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->visible(fn (Get $get): bool => $get('type') === ReturnType::CUSTOMER_RETURN),

                            Select::make('customer_id')
                                ->relationship('order.customer', 'name')
                                ->label(__('Customer'))
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get): bool => $get('type') === ReturnType::CUSTOMER_RETURN),

                            Select::make('supplier_id')
                                ->relationship('order.supplier', 'company_name')
                                ->label(__('Supplier'))
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get): bool => $get('type') === ReturnType::SUPPLIER_RETURN),

                            Select::make('processed_by')
                                ->relationship('processedBy', 'first_name')
                                ->label(__('Processed By'))
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->columns(2),

                    Step::make(__('Return Details'))
                        ->description(__('Status, reason, and notes'))
                        ->schema([
                            Select::make('status')
                                ->label(__('Status'))
                                ->options(ReturnStatus::class)
                                ->default(ReturnStatus::DRAFT)
                                ->required(),

                            Select::make('reason')
                                ->label(__('Reason'))
                                ->options(ReturnReason::class)
                                ->required(),

                            Textarea::make('notes')
                                ->label(__('Notes'))
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Step::make(__('Return Items'))
                        ->description(__('Add products to return'))
                        ->schema([
                            Repeater::make('returnDeliveryLines')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->label(__('Product'))
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpan(3),

                                    TextInput::make('quantity')
                                        ->label(__('Quantity'))
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1)
                                        ->columnSpan(1),

                                    TextInput::make('unit_price')
                                        ->label(__('Unit Price'))
                                        ->numeric()
                                        ->required()
                                        ->default(0)
                                        ->prefix('HUF')
                                        ->columnSpan(2),

                                    Select::make('condition')
                                        ->label(__('Condition'))
                                        ->options(ProductCondition::class)
                                        ->required()
                                        ->default(ProductCondition::GOOD)
                                        ->columnSpan(2),

                                    Select::make('return_reason')
                                        ->label(__('Return Reason'))
                                        ->options(ReturnReason::class)
                                        ->required()
                                        ->columnSpan(2),

                                    TextInput::make('batch_number')
                                        ->label(__('Batch Number'))
                                        ->columnSpan(2),

                                    Textarea::make('note')
                                        ->label(__('Note'))
                                        ->columnSpan(4),
                                ])
                                ->columns(4)
                                ->defaultItems(1)
                                ->reorderable(false)
                                ->collapsible(),
                        ]),
                ])
                    ->skippable()
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" size="sm">
                            {{ __('Create') }}
                        </x-filament::button>
                    BLADE))),
            ]);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = ReturnDelivery::query()->create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title(__('Return Delivery created'))
            ->success()
            ->send();

        $this->redirect(route('dashboard.return-deliveries.edit', $record), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.return-deliveries.create-return-delivery');
    }
}
