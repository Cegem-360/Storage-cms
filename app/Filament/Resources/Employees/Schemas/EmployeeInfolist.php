<?php

declare(strict_types=1);

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal Information'))
                    ->schema([
                        TextEntry::make('employee_code'),
                        TextEntry::make('first_name'),
                        TextEntry::make('last_name'),
                        TextEntry::make('phone')
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make(__('Work Information'))
                    ->schema([
                        TextEntry::make('position')
                            ->placeholder('-'),
                        TextEntry::make('department')
                            ->placeholder('-'),
                        TextEntry::make('warehouse.name')
                            ->label(__('Warehouse'))
                            ->placeholder('-'),
                        TextEntry::make('user.name')
                            ->label(__('Linked user'))
                            ->placeholder('-'),
                        IconEntry::make('is_active')
                            ->label(__('Active'))
                            ->boolean(),
                    ])
                    ->columns(2),

                Section::make(__('Timestamps'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Employee $record): bool => $record->trashed()),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
