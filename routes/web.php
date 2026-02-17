<?php

declare(strict_types=1);

use App\Livewire\Page\DashboardPage;
use App\Livewire\Page\ProfilePage;
use App\Livewire\Page\SettingsPage;
use App\Livewire\Pages\Batches\CreateBatch;
use App\Livewire\Pages\Batches\EditBatch;
use App\Livewire\Pages\Batches\ListBatches;
use App\Livewire\Pages\Categories\CreateCategory;
use App\Livewire\Pages\Categories\EditCategory;
use App\Livewire\Pages\Categories\ListCategories;
use App\Livewire\Pages\Categories\ViewCategory;
use App\Livewire\Pages\CnCodes\CreateCnCode;
use App\Livewire\Pages\CnCodes\EditCnCode;
use App\Livewire\Pages\CnCodes\ListCnCodes;
use App\Livewire\Pages\Customers\CreateCustomer;
use App\Livewire\Pages\Customers\EditCustomer;
use App\Livewire\Pages\Customers\ListCustomers;
use App\Livewire\Pages\Customers\ViewCustomer;
use App\Livewire\Pages\Employees\CreateEmployee;
use App\Livewire\Pages\Employees\EditEmployee;
use App\Livewire\Pages\Employees\ListEmployees;
use App\Livewire\Pages\Employees\ViewEmployee;
use App\Livewire\Pages\Intrastat\CreateDeclaration;
use App\Livewire\Pages\Intrastat\EditDeclaration;
use App\Livewire\Pages\Intrastat\ListDeclarations;
use App\Livewire\Pages\Intrastat\ListInbounds;
use App\Livewire\Pages\Intrastat\ListOutbounds;
use App\Livewire\Pages\Inventories\CreateInventory;
use App\Livewire\Pages\Inventories\EditInventory;
use App\Livewire\Pages\Inventories\ListInventories;
use App\Livewire\Pages\Orders\CreateOrder;
use App\Livewire\Pages\Orders\EditOrder;
use App\Livewire\Pages\Orders\ListOrders;
use App\Livewire\Pages\Orders\ViewOrder;
use App\Livewire\Pages\Products\CreateProduct;
use App\Livewire\Pages\Products\EditProduct;
use App\Livewire\Pages\Products\ListProducts;
use App\Livewire\Pages\Products\ViewProduct;
use App\Livewire\Pages\Receipts\CreateReceipt;
use App\Livewire\Pages\Receipts\EditReceipt;
use App\Livewire\Pages\Receipts\ListReceipts;
use App\Livewire\Pages\Receipts\ViewReceipt;
use App\Livewire\Pages\Reports\ExpectedArrivals;
use App\Livewire\Pages\Reports\StockOverview;
use App\Livewire\Pages\Reports\ValuationReport;
use App\Livewire\Pages\ReturnDeliveries\CreateReturnDelivery;
use App\Livewire\Pages\ReturnDeliveries\EditReturnDelivery;
use App\Livewire\Pages\ReturnDeliveries\ListReturnDeliveries;
use App\Livewire\Pages\ReturnDeliveries\ViewReturnDelivery;
use App\Livewire\Pages\Stocks\CreateStock;
use App\Livewire\Pages\Stocks\EditStock;
use App\Livewire\Pages\Stocks\ListStocks;
use App\Livewire\Pages\Stocks\ViewStock;
use App\Livewire\Pages\Suppliers\CreateSupplier;
use App\Livewire\Pages\Suppliers\EditSupplier;
use App\Livewire\Pages\Suppliers\ListSuppliers;
use App\Livewire\Pages\Suppliers\ViewSupplier;
use App\Livewire\Pages\Users\CreateUser;
use App\Livewire\Pages\Users\EditUser;
use App\Livewire\Pages\Users\ListUsers;
use App\Livewire\Pages\Users\ViewUser;
use App\Livewire\Pages\Warehouses\CreateWarehouse;
use App\Livewire\Pages\Warehouses\EditWarehouse;
use App\Livewire\Pages\Warehouses\ListWarehouses;
use App\Livewire\Pages\Warehouses\ViewWarehouse;
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
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard')->group(function (): void {
    Route::get('/', DashboardPage::class);
    Route::get('/profile', ProfilePage::class)->name('.profile');
    Route::get('/settings', SettingsPage::class)->name('.settings');

    // Batches
    Route::get('/batches', ListBatches::class)->name('.batches');
    Route::get('/batches/create', CreateBatch::class)->name('.batches.create');
    Route::get('/batches/{record}/edit', EditBatch::class)->name('.batches.edit');

    // Categories
    Route::get('/categories', ListCategories::class)->name('.categories');
    Route::get('/categories/create', CreateCategory::class)->name('.categories.create');
    Route::get('/categories/{record}', ViewCategory::class)->name('.categories.view');
    Route::get('/categories/{record}/edit', EditCategory::class)->name('.categories.edit');

    // Inventories
    Route::get('/inventories', ListInventories::class)->name('.inventories');
    Route::get('/inventories/create', CreateInventory::class)->name('.inventories.create');
    Route::get('/inventories/{record}/edit', EditInventory::class)->name('.inventories.edit');

    // Products
    Route::get('/products', ListProducts::class)->name('.products');
    Route::get('/products/create', CreateProduct::class)->name('.products.create');
    Route::get('/products/{record}', ViewProduct::class)->name('.products.view');
    Route::get('/products/{record}/edit', EditProduct::class)->name('.products.edit');

    // Stocks
    Route::get('/stocks', ListStocks::class)->name('.stocks');
    Route::get('/stocks/create', CreateStock::class)->name('.stocks.create');
    Route::get('/stocks/{record}', ViewStock::class)->name('.stocks.view');
    Route::get('/stocks/{record}/edit', EditStock::class)->name('.stocks.edit');

    // Warehouses
    Route::get('/warehouses', ListWarehouses::class)->name('.warehouses');
    Route::get('/warehouses/create', CreateWarehouse::class)->name('.warehouses.create');
    Route::get('/warehouses/{record}', ViewWarehouse::class)->name('.warehouses.view');
    Route::get('/warehouses/{record}/edit', EditWarehouse::class)->name('.warehouses.edit');

    // Return Deliveries
    Route::get('/return-deliveries', ListReturnDeliveries::class)->name('.return-deliveries');
    Route::get('/return-deliveries/create', CreateReturnDelivery::class)->name('.return-deliveries.create');
    Route::get('/return-deliveries/{record}', ViewReturnDelivery::class)->name('.return-deliveries.view');
    Route::get('/return-deliveries/{record}/edit', EditReturnDelivery::class)->name('.return-deliveries.edit');

    // Suppliers
    Route::get('/suppliers', ListSuppliers::class)->name('.suppliers');
    Route::get('/suppliers/create', CreateSupplier::class)->name('.suppliers.create');
    Route::get('/suppliers/{record}', ViewSupplier::class)->name('.suppliers.view');
    Route::get('/suppliers/{record}/edit', EditSupplier::class)->name('.suppliers.edit');

    // Reports
    Route::get('/stock-overview', StockOverview::class)->name('.stock-overview');
    Route::get('/expected-arrivals', ExpectedArrivals::class)->name('.expected-arrivals');
    Route::get('/valuation-report', ValuationReport::class)->name('.valuation-report');

    // Customers
    Route::get('/customers', ListCustomers::class)->name('.customers');
    Route::get('/customers/create', CreateCustomer::class)->name('.customers.create');
    Route::get('/customers/{record}', ViewCustomer::class)->name('.customers.view');
    Route::get('/customers/{record}/edit', EditCustomer::class)->name('.customers.edit');

    // Orders
    Route::get('/orders', ListOrders::class)->name('.orders');
    Route::get('/orders/create', CreateOrder::class)->name('.orders.create');
    Route::get('/orders/{record}', ViewOrder::class)->name('.orders.view');
    Route::get('/orders/{record}/edit', EditOrder::class)->name('.orders.edit');

    // Receipts
    Route::get('/receipts', ListReceipts::class)->name('.receipts');
    Route::get('/receipts/create', CreateReceipt::class)->name('.receipts.create');
    Route::get('/receipts/{record}', ViewReceipt::class)->name('.receipts.view');
    Route::get('/receipts/{record}/edit', EditReceipt::class)->name('.receipts.edit');

    // CN Codes
    Route::get('/cn-codes', ListCnCodes::class)->name('.cn-codes');
    Route::get('/cn-codes/create', CreateCnCode::class)->name('.cn-codes.create');
    Route::get('/cn-codes/{record}/edit', EditCnCode::class)->name('.cn-codes.edit');

    // Intrastat
    Route::get('/intrastat-declarations', ListDeclarations::class)->name('.intrastat-declarations');
    Route::get('/intrastat-declarations/create', CreateDeclaration::class)->name('.intrastat-declarations.create');
    Route::get('/intrastat-declarations/{record}/edit', EditDeclaration::class)->name('.intrastat-declarations.edit');
    Route::get('/intrastat-inbounds', ListInbounds::class)->name('.intrastat-inbounds');
    Route::get('/intrastat-outbounds', ListOutbounds::class)->name('.intrastat-outbounds');

    // Users & Employees
    Route::get('/users', ListUsers::class)->name('.users');
    Route::get('/users/create', CreateUser::class)->name('.users.create');
    Route::get('/users/{record}', ViewUser::class)->name('.users.view');
    Route::get('/users/{record}/edit', EditUser::class)->name('.users.edit');
    Route::get('/employees', ListEmployees::class)->name('.employees');
    Route::get('/employees/create', CreateEmployee::class)->name('.employees.create');
    Route::get('/employees/{record}', ViewEmployee::class)->name('.employees.view');
    Route::get('/employees/{record}/edit', EditEmployee::class)->name('.employees.edit');
});
