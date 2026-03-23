<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Commands\FileGenerators\Resources\ResourceClassGenerator;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use App\Listeners\AssignTenantOnUserSync;
use App\Listeners\AssignUserToTenantOnTeamSync;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Madbox99\UserTeamSync\Events\TeamCreatedFromSync;
use Madbox99\UserTeamSync\Events\UserCreatedFromSync;

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
        FilamentTimezone::set('Europe/Budapest');

        $this->app->bind(BaseResourceClassGenerator::class, ResourceClassGenerator::class);

        $this->configureFilamentTranslations();
        $this->configureRateLimiting();
        $this->registerSyncListeners();

        $this->app->booted(function (): void {
            if (! app()->runningInConsole()) {
                FilamentAsset::register([
                    Js::make('echo', Vite::asset('resources/js/echo.js'))->module(),
                ]);
            }
        });

        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
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
        Action::configureUsing(fn (Action $c) => $c->translateLabel());
    }

    private function registerSyncListeners(): void
    {
        Event::listen(UserCreatedFromSync::class, AssignTenantOnUserSync::class);
        Event::listen(TeamCreatedFromSync::class, AssignUserToTenantOnTeamSync::class);
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('global', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));

        RateLimiter::for('sync-api', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
    }
}
