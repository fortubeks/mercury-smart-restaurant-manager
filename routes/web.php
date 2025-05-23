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
    //Orders
    Route::resource('orders', \App\Http\Controllers\OrderController::class);
    //Menu
    Route::resource('menu', \App\Http\Controllers\MenuController::class);
    //Menu Categories
    Route::resource('menu-categories', \App\Http\Controllers\MenuCategoryController::class);
    //Menu Items
    Route::resource('menu-items', \App\Http\Controllers\MenuItemController::class);
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
});
Route::get('setting/restaurant/edit', [RestaurantController::class, 'editRestaurant'])->name('settings.restaurant.edit');
Route::post('restaurant/update', [RestaurantController::class, 'updateRestaurant'])->name('restaurant.update');
require __DIR__ . '/auth.php';
