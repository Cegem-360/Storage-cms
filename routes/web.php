<?php

declare(strict_types=1);

use App\Livewire\Page\DashboardPage;
use App\Livewire\Page\ProfilePage;
use App\Livewire\Page\SettingsPage;
use App\Livewire\Pages\Batches\ListBatches;
use App\Livewire\Pages\Categories\ListCategories;
use App\Livewire\Pages\CnCodes\ListCnCodes;
use App\Livewire\Pages\Customers\ListCustomers;
use App\Livewire\Pages\Employees\ListEmployees;
use App\Livewire\Pages\Intrastat\ListDeclarations;
use App\Livewire\Pages\Intrastat\ListInbounds;
use App\Livewire\Pages\Intrastat\ListOutbounds;
use App\Livewire\Pages\Inventories\ListInventories;
use App\Livewire\Pages\Orders\ListOrders;
use App\Livewire\Pages\Products\ListProducts;
use App\Livewire\Pages\Receipts\ListReceipts;
use App\Livewire\Pages\Reports\ExpectedArrivals;
use App\Livewire\Pages\Reports\StockOverview;
use App\Livewire\Pages\Reports\ValuationReport;
use App\Livewire\Pages\ReturnDeliveries\ListReturnDeliveries;
use App\Livewire\Pages\Stocks\ListStocks;
use App\Livewire\Pages\Suppliers\ListSuppliers;
use App\Livewire\Pages\Users\ListUsers;
use App\Livewire\Pages\Warehouses\ListWarehouses;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): View|Factory => view('home'))->name('home');

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
    Route::get('/register', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('register');
});

// Authenticated dashboard routes
Route::middleware(['auth'])->group(function (): void {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
    Route::get('/dashboard/profile', ProfilePage::class)->name('dashboard.profile');
    Route::get('/dashboard/settings', SettingsPage::class)->name('dashboard.settings');

    // Inventory Management routes - Livewire pages
    Route::get('/dashboard/batches', ListBatches::class)->name('dashboard.batches');
    Route::get('/dashboard/categories', ListCategories::class)->name('dashboard.categories');
    Route::get('/dashboard/inventories', ListInventories::class)->name('dashboard.inventories');
    Route::get('/dashboard/products', ListProducts::class)->name('dashboard.products');
    Route::get('/dashboard/stocks', ListStocks::class)->name('dashboard.stocks');
    Route::get('/dashboard/warehouses', ListWarehouses::class)->name('dashboard.warehouses');
    Route::get('/dashboard/return-deliveries', ListReturnDeliveries::class)->name('dashboard.return-deliveries');
    Route::get('/dashboard/suppliers', ListSuppliers::class)->name('dashboard.suppliers');

    // Reports - Livewire pages
    Route::get('/dashboard/stock-overview', StockOverview::class)->name('dashboard.stock-overview');
    Route::get('/dashboard/expected-arrivals', ExpectedArrivals::class)->name('dashboard.expected-arrivals');
    Route::get('/dashboard/valuation-report', ValuationReport::class)->name('dashboard.valuation-report');

    // Sales routes - Livewire pages
    Route::get('/dashboard/customers', ListCustomers::class)->name('dashboard.customers');
    Route::get('/dashboard/orders', ListOrders::class)->name('dashboard.orders');
    Route::get('/dashboard/receipts', ListReceipts::class)->name('dashboard.receipts');

    // Intrastat routes - Livewire pages
    Route::get('/dashboard/cn-codes', ListCnCodes::class)->name('dashboard.cn-codes');
    Route::get('/dashboard/intrastat-declarations', ListDeclarations::class)->name('dashboard.intrastat-declarations');
    Route::get('/dashboard/intrastat-inbounds', ListInbounds::class)->name('dashboard.intrastat-inbounds');
    Route::get('/dashboard/intrastat-outbounds', ListOutbounds::class)->name('dashboard.intrastat-outbounds');

    // Administration routes - Livewire pages
    Route::get('/dashboard/users', ListUsers::class)->name('dashboard.users');
    Route::get('/dashboard/employees', ListEmployees::class)->name('dashboard.employees');
});
