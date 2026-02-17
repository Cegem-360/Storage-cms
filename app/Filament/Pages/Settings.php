<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Team;
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
        $team = auth()->user()->team;
        $team->load('settings');

        $this->form->fill([
            'low_stock_threshold' => $team->getSetting('low_stock_threshold', 10),
            'auto_reorder_enabled' => (bool) $team->getSetting('auto_reorder_enabled', false),
            'notification_email' => $team->getSetting('notification_email'),
            'ai_provider' => $team->getSetting('ai_provider', 'openai'),
            'ai_api_key' => $team->getSetting('ai_api_key'),
            'ai_model' => $team->getSetting('ai_model', 'gpt-4o-mini'),
            'billingo_api_key' => $team->getSetting('billingo_api_key'),
            'billingo_block_id' => $team->getSetting('billingo_block_id'),
            'billingo_enabled' => (bool) $team->getSetting('billingo_enabled', false),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inventory Settings')
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

                Section::make('Notification Settings')
                    ->description(__('Configure system notification preferences'))
                    ->schema([
                        TextInput::make('notification_email')
                            ->label('Notification Email')
                            ->helperText(__('Email address for system alerts'))
                            ->email()
                            ->maxLength(255),
                    ]),

                Section::make('AI Assistant')
                    ->description(__('Configure AI assistant for intelligent help'))
                    ->schema([
                        Select::make('ai_provider')
                            ->label(__('AI Provider'))
                            ->options([
                                'openai' => 'OpenAI',
                                'anthropic' => 'Anthropic (Claude)',
                            ])
                            ->default('openai')
                            ->required(),
                        TextInput::make('ai_api_key')
                            ->label(__('AI API Key'))
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText(__('Your OpenAI or Anthropic API key')),
                        TextInput::make('ai_model')
                            ->label(__('AI Model'))
                            ->default('gpt-4o-mini')
                            ->maxLength(100)
                            ->helperText(__('e.g. gpt-4o-mini, claude-sonnet-4-5-20250929')),
                    ])
                    ->columns(2),

                Section::make('Billingo')
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
        $team = auth()->user()->team;

        $team->setSetting('low_stock_threshold', $data['low_stock_threshold']);
        $team->setSetting('auto_reorder_enabled', $data['auto_reorder_enabled']);
        $team->setSetting('notification_email', $data['notification_email']);
        $team->setSetting('ai_provider', $data['ai_provider']);
        $team->setSetting('ai_api_key', $data['ai_api_key']);
        $team->setSetting('ai_model', $data['ai_model']);
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
