<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('General'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Team Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make(__('AI Token Limits'))
                    ->description(__('Configure monthly AI token usage limits for this team'))
                    ->schema([
                        TextInput::make('ai_monthly_token_limit')
                            ->label(__('Monthly Token Limit'))
                            ->helperText(__('Maximum number of AI tokens per month. Set to 0 for unlimited.'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->suffix(__('tokens')),
                        Placeholder::make('ai_current_usage')
                            ->label(__('Current Month Usage'))
                            ->content(fn ($record) => $record
                                ? number_format($record->aiTokenUsages()->where('month', now()->format('Y-m'))->first()?->total_tokens ?? 0).' '.__('tokens')
                                : '0 '.__('tokens'))
                            ->visibleOn('edit'),
                    ])
                    ->columns(2),
            ]);
    }
}
