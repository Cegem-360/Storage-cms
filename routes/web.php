<?php

declare(strict_types=1);

use App\Livewire\Page\DashboardPage;
use App\Livewire\Page\ProfilePage;
use App\Livewire\Page\SettingsPage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): View|Factory {
    return view('home');
})->name('home');

Route::get('/language/{locale}', function (string $locale): RedirectResponse {
    if (! in_array($locale, ['en', 'hu'], true)) {
        abort(400);
    }
    $cookie = cookie('locale', $locale, 60 * 24 * 365);
    $referer = request()->headers->get('referer');
    $redirectUrl = $referer ?: url()->previous();

    return redirect($redirectUrl)->withCookie($cookie);
})->name('language.switch');

// Guest routes - redirect to Filament auth pages
Route::middleware(['guest'])->group(function (): void {
    Route::get('/login', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('login');
    Route::get('/register', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.register'))->name('register');
});

// Authenticated dashboard routes
Route::middleware(['auth'])->group(function (): void {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
    Route::get('/dashboard/profile', ProfilePage::class)->name('dashboard.profile');
    Route::get('/dashboard/settings', SettingsPage::class)->name('dashboard.settings');

    // Inventory routes - redirect to Filament resources
    Route::get('/dashboard/products', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.products.index'))->name('dashboard.products');
    Route::get('/dashboard/categories', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.categories.index'))->name('dashboard.categories');
    Route::get('/dashboard/warehouses', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.warehouses.index'))->name('dashboard.warehouses');
    Route::get('/dashboard/stocks', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.stocks.index'))->name('dashboard.stocks');

    // Operations routes - redirect to Filament resources
    Route::get('/dashboard/orders', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.orders.index'))->name('dashboard.orders');
    Route::get('/dashboard/receipts', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.receipts.index'))->name('dashboard.receipts');
    Route::get('/dashboard/movements', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.stock-movements.index'))->name('dashboard.movements');
    Route::get('/dashboard/inventories', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.inventories.index'))->name('dashboard.inventories');

    // Partners routes - redirect to Filament resources
    Route::get('/dashboard/suppliers', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.suppliers.index'))->name('dashboard.suppliers');
    Route::get('/dashboard/customers', fn (): Redirector|RedirectResponse => to_route('filament.admin.resources.customers.index'))->name('dashboard.customers');
});
