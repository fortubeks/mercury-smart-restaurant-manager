<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
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
    Route::get('settings', [DashboardController::class, 'settings'])->name('settings');

    //Restaurant
    Route::resource('restaurants', RestaurantController::class);

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
    //Printer
    Route::get('printer/cart/{id}', [\App\Http\Controllers\PrinterController::class, 'printCart'])->name('printer.cart');
    Route::get('printer/order/{order}', [\App\Http\Controllers\PrinterController::class, 'printOrder'])->name('printer.order');
    Route::get('printer/kitchen-slip/{id}', [\App\Http\Controllers\PrinterController::class, 'printKitchenSlip'])->name('printer.kitchen-slip');
    //Stores
    Route::resource('stores', \App\Http\Controllers\StoreController::class);
    //Store Item Categories
    Route::resource('store-item-categories', \App\Http\Controllers\StoreItemCategoryController::class);
    //Store Items
    Route::resource('store-items', \App\Http\Controllers\StoreItemController::class);
    //Expense Categories
    Route::resource('expense-categories', \App\Http\Controllers\ExpenseCategoryController::class);
    //Expense Items
    Route::resource('expense-items', \App\Http\Controllers\ExpenseItemController::class);
    //Expenses
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    //Purchases
    Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    //Suppliers
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    //Users
    Route::resource('users', \App\Http\Controllers\UserController::class);
    //Settings
    Route::resource('settings', \App\Http\Controllers\AppSettingController::class);

    Route::post('set-outlet', [\App\Http\Controllers\OutletController::class, 'setOutlet'])->name('set.outlet');
});
Route::get('setting/restaurant/edit', [RestaurantController::class, 'editRestaurant'])->name('settings.restaurant.edit');
Route::post('restaurant/update', [RestaurantController::class, 'updateRestaurant'])->name('restaurant.update');
require __DIR__ . '/auth.php';
