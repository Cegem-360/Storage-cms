<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\BatchController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CnCodeController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\IntrastatDeclarationController;
use App\Http\Controllers\Api\V1\IntrastatLineController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\InventoryLineController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderLineController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ReceiptController;
use App\Http\Controllers\Api\V1\ReceiptLineController;
use App\Http\Controllers\Api\V1\ReturnDeliveryController;
use App\Http\Controllers\Api\V1\ReturnDeliveryLineController;
use App\Http\Controllers\Api\V1\StockController;
use App\Http\Controllers\Api\V1\StockMovementController;
use App\Http\Controllers\Api\V1\StockTransactionController;
use App\Http\Controllers\Api\V1\SupplierController;
use App\Http\Controllers\Api\V1\SupplierPriceController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WarehouseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

Route::post('/tokens/create', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'device_name' => ['required'],
    ]);

    $user = User::query()->where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => [__('The provided credentials are incorrect.')],
        ]);
    }

    return ['token' => $user->createToken($request->device_name)->plainTextToken];
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::delete('/tokens/revoke', function (Request $request) {
        /** @var PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();
        $token->delete();

        return response()->noContent();
    });

    Route::prefix('v1')->group(function () {
        // Full CRUD resources
        Route::apiResources([
            'products' => ProductController::class,
            'categories' => CategoryController::class,
            'customers' => CustomerController::class,
            'suppliers' => SupplierController::class,
            'warehouses' => WarehouseController::class,
            'employees' => EmployeeController::class,
            'batches' => BatchController::class,
            'supplier-prices' => SupplierPriceController::class,
            'cn-codes' => CnCodeController::class,
            'users' => UserController::class,
        ]);

        // Read-only resources
        Route::apiResources([
            'orders' => OrderController::class,
            'stocks' => StockController::class,
            'stock-transactions' => StockTransactionController::class,
            'stock-movements' => StockMovementController::class,
            'receipts' => ReceiptController::class,
            'inventories' => InventoryController::class,
            'return-deliveries' => ReturnDeliveryController::class,
            'intrastat-declarations' => IntrastatDeclarationController::class,
        ], ['only' => ['index', 'show']]);

        // Nested child resources
        Route::apiResource('orders.lines', OrderLineController::class)->only(['index', 'show']);
        Route::apiResource('receipts.lines', ReceiptLineController::class)->only(['index', 'show']);
        Route::apiResource('inventories.lines', InventoryLineController::class)->only(['index', 'show']);
        Route::apiResource('return-deliveries.lines', ReturnDeliveryLineController::class)->only(['index', 'show']);
        Route::apiResource('intrastat-declarations.lines', IntrastatLineController::class)->only(['index', 'show']);
    });
});
