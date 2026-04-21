<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Enums\NavigationGroup;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\EditProfile;
use App\Filament\Pages\RegisterTeam;
use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\SetLocale;
use App\Models\Team;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup as FilamentNavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Madbox99\FilamentChatWidget\FilamentChatWidgetPlugin;
use Madbox99\UserTeamSync\Receiver\Http\Middleware\EnsureUserHasActiveSubscription;

final class AdminPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('app')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->brandLogo(asset('images/logo.png'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Figtree')
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->collapsibleNavigationGroups(false)
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogoHeight('2rem')
            ->navigationGroups([
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::INVENTORY_MANAGEMENT->getLabel()),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::SALES->getLabel()),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::REPORTS->getLabel()),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::ADMINISTRATION->getLabel()),
                FilamentNavigationGroup::make()
                    ->label(fn (): string => NavigationGroup::INTRASTAT->getLabel()),
            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn (): View => view('filament.sidebar-quick-links'),
            )->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): View => view('filament.sidebar-transition-script'),
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): View => view('filament.topbar-items'),
            )
            ->userMenuItems([
                'profile' => fn (Action $action): Action => $action
                    ->url('https://cegem360.eu/admin/profile', shouldOpenInNewTab: true),
            ])
            ->tenant(Team::class, slugAttribute: 'slug')
            ->tenantRegistration(RegisterTeam::class)
            ->tenantMenu(fn (): bool => Auth::check() && (Auth::user()->isAdmin() || Auth::user()->teams()->count() > 1))
            ->tenantMiddleware([
                ApplyTenantScopes::class,
            ], isPersistent: true)
            ->plugins([
                FilamentChatWidgetPlugin::make(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureUserHasActiveSubscription::class,
            ])
            ->spa()
            ->spaUrlExceptions([
                '*/language/*',
                '*/google/oauth/*',
            ]);
    }
}
