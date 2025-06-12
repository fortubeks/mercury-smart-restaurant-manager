<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Google sign-in
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Settings
    Route::get('settings', [\App\Http\Controllers\AppSettingController::class, 'settings'])->name('settings');

    //Restaurant
    Route::resource('restaurants', \App\Http\Controllers\RestaurantController::class);

    //Accounting
    Route::resource('daily-sales', \App\Http\Controllers\DailySaleController::class);
    Route::resource('bank-accounts', \App\Http\Controllers\BankAccountController::class);
    Route::resource('bank-account-transactions', \App\Http\Controllers\BankAccountTransactionController::class);
    //report.index
    Route::get('reports/index', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('report/daily-sales', [\App\Http\Controllers\DailySaleController::class, 'index'])->name('reports.daily-sales-summary');

    Route::post('report/generate-report', [\App\Http\Controllers\ReportController::class, 'generateReport'])->name('reports.generate-report');
    Route::get('/report/download-report', [\App\Http\Controllers\ReportController::class, 'downloadReport'])->name('reports.download');

    //Customers
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::get('search/customers', [\App\Http\Controllers\CustomerController::class, 'search'])->name('search.customers');

    //Orders
    Route::resource('orders', \App\Http\Controllers\OrderController::class);

    //Menu
    Route::resource('menu', \App\Http\Controllers\MenuController::class);

    //Menu Categories
    Route::resource('menu-categories', \App\Http\Controllers\MenuCategoryController::class);

    //Menu Items
    Route::resource('menu-items', \App\Http\Controllers\MenuItemController::class);

    //search menu items
    Route::get('search/menu-items', [\App\Http\Controllers\MenuItemController::class, 'search'])->name('search.menu-items');

    //Cart routes
    Route::post('cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('cart/edit', [\App\Http\Controllers\CartController::class, 'edit'])->name('cart.edit');
    Route::post('cart/delete', [\App\Http\Controllers\CartController::class, 'delete'])->name('cart.delete');
    Route::post('cart/update-order-information', [\App\Http\Controllers\CartController::class, 'updateOrderInformation'])->name('cart.update.order-info');
    //Printer
    Route::get('printer/cart/{id}', [\App\Http\Controllers\PrinterController::class, 'printCart'])->name('printer.cart');
    Route::get('printer/order/{order}', [\App\Http\Controllers\PrinterController::class, 'printOrder'])->name('printer.order');
    Route::get('printer/kitchen-slip/{id}', [\App\Http\Controllers\PrinterController::class, 'printKitchenSlip'])->name('printer.kitchen-slip');
    //Stores
    Route::resource('stores', \App\Http\Controllers\StoreController::class);
    Route::get('store/dashboard', [\App\Http\Controllers\StoreController::class, 'dashboard'])->name('store.dashboard');
    //Store Item Categories
    Route::resource('store-item-categories', \App\Http\Controllers\StoreItemCategoryController::class);
    //Store Items
    Route::resource('store-items', \App\Http\Controllers\StoreItemController::class);

    Route::get('store-item/import', [\App\Http\Controllers\StoreItemController::class, 'viewImportItemsForm']);

    Route::get('store-item/import/download-sample', [\App\Http\Controllers\StoreItemController::class, 'downloadSampleExcel'])->name('store-item.import.download-sample');
    Route::post('store-item/import/new', [\App\Http\Controllers\StoreItemController::class, 'importItems'])->name('store-item.import.new');

    Route::post('store-item/import/existing', [\App\Http\Controllers\StoreItemController::class, 'importItemsByUpdate'])->name('store-item.import.existing');

    Route::get('store-item/download-existing', [\App\Http\Controllers\StoreItemController::class, 'export'])->name('store-item.download-existing');

    Route::get('store/give-items', [\App\Http\Controllers\StoreItemController::class, 'viewGiveItemsForm'])->name('store.give-items');
    Route::post('store/give-items', [\App\Http\Controllers\StoreItemController::class, 'giveItems'])->name('store.give-items.post');

    Route::get('migrate-items', [\App\Http\Controllers\StoreItemController::class, 'viewMigrateItemsForm']);
    Route::post('migrate-items', [\App\Http\Controllers\StoreItemController::class, 'migrateItems']);

    Route::get('incoming-inventories', [\App\Http\Controllers\StoreInventoryController::class, 'incomingItems'])->name('incoming-inventories');
    Route::get('outgoing-inventories', [\App\Http\Controllers\StoreInventoryController::class, 'outgoingItems'])->name('outgoing-inventories');

    Route::get('stock-count', [\App\Http\Controllers\StoreInventoryController::class, 'viewStockCountPage']);
    Route::post('stock-count', [\App\Http\Controllers\StoreInventoryController::class, 'getStockCount']);

    Route::get('export-items', [\App\Http\Controllers\StoreItemController::class, 'export']);

    Route::post('store-item-activity/delete/{id}', [\App\Http\Controllers\StoreInventoryController::class, 'deleteStoreActivity']);

    Route::post('store/report-damaged-item', [\App\Http\Controllers\StoreItemController::class, 'reportDamagedItem'])->name('store.reportdamaged');
    //Expense Categories
    Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class);
    //Expense Items
    Route::resource('expense-items', \App\Http\Controllers\ExpenseItemController::class);
    //Outgoing Payments
    Route::resource('outgoing-payments', \App\Http\Controllers\OutgoingPaymentController::class);
    Route::get('outgoing-payments/search', [\App\Http\Controllers\OutgoingPaymentController::class, 'search'])->name('outgoing-payments.search');
    Route::post('outgoing-payment/purchase', [\App\Http\Controllers\OutgoingPaymentController::class, 'storePurchasePayment'])->name('outgoing-payments.purchase.store');
    Route::resource('incoming-payments', \App\Http\Controllers\IncomingPaymentController::class);
    //Expenses
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::get('expense/all', [\App\Http\Controllers\ExpenseController::class, 'allExpenses'])->name('expenses.all');
    Route::get('expense/summary', [\App\Http\Controllers\ExpenseController::class, 'summary'])->name('expenses.summary');
    Route::get('expense/search', [\App\Http\Controllers\ExpenseController::class, 'search'])->name('expense.search');
    //Purchases
    Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    Route::get('purchase/all', [\App\Http\Controllers\PurchaseController::class, 'allExpenses'])->name('purchases.all');
    Route::get('purchase/summary', [\App\Http\Controllers\PurchaseController::class, 'summary'])->name('purchases.summary');
    Route::get('purchase/search', [\App\Http\Controllers\PurchaseController::class, 'search'])->name('purchases.search');
    //Suppliers
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    //Users
    Route::resource('users', \App\Http\Controllers\UserController::class);
    //Settings
    Route::resource('settings', \App\Http\Controllers\AppSettingController::class);
    Route::get('setting/app', [\App\Http\Controllers\AppSettingController::class, 'showAppSettingsForm'])->name('settings.app.settings');
    Route::post('setting/app', [\App\Http\Controllers\AppSettingController::class, 'updateAppSettings'])->name('settings.app.settings.post');

    Route::get('setting/bulk-sms', [\App\Http\Controllers\BulkMessageController::class, 'showSettingsForm'])->name('settings.bulk-sms');
    Route::post('setting/bulk-sms', [\App\Http\Controllers\BulkMessageController::class, 'updateSettings'])->name('settings.bulk-sms.post');
    //Booking Engine
    Route::get('setting/booking-engine', [\App\Http\Controllers\AppSettingController::class, 'showBookingEngineSettingsForm'])->name('settings.booking-engine');
    Route::post('setting/booking-engine', [\App\Http\Controllers\AppSettingController::class, 'updateBookingEngineSettings'])->name('settings.booking-engine.post');
    //Taxes
    Route::resource('taxes', \App\Http\Controllers\TaxController::class);

    Route::post('set-outlet', [\App\Http\Controllers\OutletController::class, 'setOutlet'])->name('set.outlet');

    //set shift
    Route::post('shift/set', [\App\Http\Controllers\UserController::class, 'setShift'])->name('shift.set');
});
Route::get('setting/restaurant/edit', [\App\Http\Controllers\RestaurantController::class, 'editRestaurant'])->name('settings.restaurant.edit');
Route::post('restaurant/update', [\App\Http\Controllers\RestaurantController::class, 'updateRestaurant'])->name('restaurant.update');
require __DIR__ . '/auth.php';
