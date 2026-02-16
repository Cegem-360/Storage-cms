<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Team;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;

final class Settings extends Page
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 99;

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
        $team = $this->getTeam();
        $team->load('settings');

        $this->form->fill([
            'low_stock_threshold' => $team->getSetting('low_stock_threshold', 10),
            'auto_reorder_enabled' => (bool) $team->getSetting('auto_reorder_enabled', false),
            'notification_email' => $team->getSetting('notification_email'),
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
                            ->label('Low Stock Threshold')
                            ->helperText(__('Default threshold for low stock alerts'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(10),

                        Toggle::make('auto_reorder_enabled')
                            ->label('Enable Auto Reorder')
                            ->helperText(__('Automatically create purchase orders when stock falls below reorder point')),
                    ])
                    ->columns(2),

                Section::make(__('Notification Settings'))
                    ->description(__('Configure system notification preferences'))
                    ->schema([
                        TextInput::make('notification_email')
                            ->label('Notification Email')
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
        $team = $this->getTeam();

        $team->setSetting('low_stock_threshold', $data['low_stock_threshold']);
        $team->setSetting('auto_reorder_enabled', $data['auto_reorder_enabled']);
        $team->setSetting('notification_email', $data['notification_email']);

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
                ->label('Save Settings')
                ->action('save')
                ->icon(Heroicon::Check),
        ];
    }

    private function getTeam(): Team
    {
        return auth()->user()->team;
    }
}
