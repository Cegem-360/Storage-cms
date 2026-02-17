<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\Column;
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
        Field::configureUsing(fn (Field $c) => $c->translateLabel());
        Column::configureUsing(fn (Column $c) => $c->translateLabel());
        Entry::configureUsing(fn (Entry $c) => $c->translateLabel());
        Filter::configureUsing(fn (Filter $c) => $c->translateLabel());
        SelectFilter::configureUsing(fn (SelectFilter $c) => $c->translateLabel());
        Tab::configureUsing(fn (Tab $c) => $c->translateLabel());
        Section::configureUsing(fn (Section $c) => $c->translateLabel());
    }
}
