<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Override;
use UnitEnum;

final class Settings extends Page
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.settings';

    protected static ?string $title = 'System Settings';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('System Settings');
    }

    public function mount(): void
    {
        $team = Auth::user()->team;
        $team->load('settings');

        $this->form->fill([
            'default_country' => $team->getSetting('default_country', 'Magyarország'),
            'currency' => $team->getSetting('currency', 'HUF'),
            'low_stock_threshold' => $team->getSetting('low_stock_threshold', 10),
            'auto_reorder_enabled' => (bool) $team->getSetting('auto_reorder_enabled', false),
            'notification_email' => $team->getSetting('notification_email'),
            'billingo_api_key' => $team->getSetting('billingo_api_key'),
            'billingo_block_id' => $team->getSetting('billingo_block_id'),
            'billingo_enabled' => (bool) $team->getSetting('billingo_enabled', false),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('general_settings')
                    ->label(__('General Settings'))
                    ->description(__('Configure general system preferences'))
                    ->schema([
                        TextInput::make('default_country')
                            ->label(__('Default country'))
                            ->helperText(__('Default country for new addresses'))
                            ->maxLength(255)
                            ->default('Magyarország'),

                        Select::make('currency')
                            ->label(__('Default Currency'))
                            ->options([
                                'HUF' => __('Hungarian Forint').' (HUF)',
                                'EUR' => __('Euro').' (EUR)',
                                'USD' => __('US Dollar').' (USD)',
                            ])
                            ->default('HUF')
                            ->required()
                            ->helperText(__('Currency used for prices and financial reports')),
                    ]),

                Section::make('inventory_settings')
                    ->label(__('Inventory Settings'))
                    ->description(__('Configure inventory management preferences'))
                    ->schema([
                        TextInput::make('low_stock_threshold')
                            ->helperText(__('Default threshold for low stock alerts'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(10),

                        Toggle::make('auto_reorder_enabled')
                            ->label(__('Enable Auto Reorder'))
                            ->helperText(__('Automatically create purchase orders when stock falls below reorder point')),
                    ])
                    ->columns(2),

                Section::make('notification_settings')
                    ->label(__('Notification Settings'))
                    ->description(__('Configure system notification preferences'))
                    ->schema([
                        TextInput::make('notification_email')
                            ->helperText(__('Email address for system alerts'))
                            ->email()
                            ->maxLength(255),
                    ]),

                Section::make('billingo')
                    ->label(__('Billingo'))
                    ->description(__('Configure Billingo invoice integration'))
                    ->schema([
                        Toggle::make('billingo_enabled')
                            ->label(__('Enable Billingo Integration'))
                            ->helperText(__('Generate invoices via Billingo when completing receipts')),
                        TextInput::make('billingo_api_key')
                            ->label(__('Billingo API Key'))
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        TextInput::make('billingo_block_id')
                            ->label(__('Billingo Block ID'))
                            ->numeric()
                            ->helperText(__('Invoice block ID from Billingo')),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $team = Auth::user()->team;

        $team->setSetting('default_country', $data['default_country']);
        $team->setSetting('currency', $data['currency']);
        $team->setSetting('low_stock_threshold', $data['low_stock_threshold']);
        $team->setSetting('auto_reorder_enabled', $data['auto_reorder_enabled']);
        $team->setSetting('notification_email', $data['notification_email']);
        $team->setSetting('billingo_api_key', $data['billingo_api_key']);
        $team->setSetting('billingo_block_id', $data['billingo_block_id']);
        $team->setSetting('billingo_enabled', $data['billingo_enabled']);

        Notification::make()
            ->success()
            ->title(__('Settings saved'))
            ->send();
    }

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Settings'))
                ->action('save')
                ->icon(Heroicon::Check),
        ];
    }
}
