<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;

final class Settings extends Page
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.settings';

    protected static ?string $title = 'System Settings';

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public function getTitle(): string
    {
        return __('System Settings');
    }

    public function mount(): void
    {
        $this->form->fill([
            'low_stock_threshold' => Cache::get('settings.low_stock_threshold', 10),
            'auto_reorder_enabled' => Cache::get('settings.auto_reorder_enabled', false),
            'default_warehouse_id' => Cache::get('settings.default_warehouse_id'),
            'notification_email' => Cache::get('settings.notification_email'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Inventory Settings'))
                    ->description(__('Configure inventory management preferences'))
                    ->schema([
                        TextInput::make('low_stock_threshold')
                            ->label(__('Low Stock Threshold'))
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

                Section::make(__('Notification Settings'))
                    ->description(__('Configure system notification preferences'))
                    ->schema([
                        TextInput::make('notification_email')
                            ->label(__('Notification Email'))
                            ->helperText(__('Email address for system alerts'))
                            ->email()
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Cache::forever('settings.low_stock_threshold', $data['low_stock_threshold']);
        Cache::forever('settings.auto_reorder_enabled', $data['auto_reorder_enabled']);
        Cache::forever('settings.notification_email', $data['notification_email']);

        Notification::make()
            ->success()
            ->title(__('Settings saved'))
            ->send();
    }

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
