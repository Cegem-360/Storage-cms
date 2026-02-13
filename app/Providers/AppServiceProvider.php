<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegistrationResponseContract::class, RegistrationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureFilamentTranslations();
    }

    private function configureFilamentTranslations(): void
    {
        TextInput::configureUsing(fn (TextInput $c) => $c->translateLabel());
        Textarea::configureUsing(fn (Textarea $c) => $c->translateLabel());
        Select::configureUsing(fn (Select $c) => $c->translateLabel());
        Toggle::configureUsing(fn (Toggle $c) => $c->translateLabel());
        Checkbox::configureUsing(fn (Checkbox $c) => $c->translateLabel());
        DatePicker::configureUsing(fn (DatePicker $c) => $c->translateLabel());
        DateTimePicker::configureUsing(fn (DateTimePicker $c) => $c->translateLabel());

        TextColumn::configureUsing(fn (TextColumn $c) => $c->translateLabel());
        IconColumn::configureUsing(fn (IconColumn $c) => $c->translateLabel());

        TextEntry::configureUsing(fn (TextEntry $c) => $c->translateLabel());
        IconEntry::configureUsing(fn (IconEntry $c) => $c->translateLabel());

        Filter::configureUsing(fn (Filter $c) => $c->translateLabel());
        SelectFilter::configureUsing(fn (SelectFilter $c) => $c->translateLabel());
    }
}
