<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\IncomingGoodsController;
use App\Http\Controllers\OutgoingGoodsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminCategorySettingsController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| Normal User AS barista Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user-access:barista'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userView'])->name('home');
    Route::get('/items', [ItemController::class, 'indexUser'])->name('user.items.index');

    // Barang Masuk
    Route::get('/barang-masuk', [IncomingGoodsController::class, 'indexBarista'])->name('users.barang-masuk.index');
    Route::get('/barang-masuk/create', [IncomingGoodsController::class, 'createBarista'])->name('users.barang-masuk.create');
    Route::post('/barang-masuk', [IncomingGoodsController::class, 'storeBarista'])->name('users.barang-masuk.store');
    Route::get('/barang-masuk/{id}', [IncomingGoodsController::class, 'showBarista'])->name('users.barang-masuk.show');

    // Barang keluar
    Route::get('/barang-keluar', [OutgoingGoodsController::class, 'indexBarista'])->name('users.barang-keluar.index');
    Route::get('/barang-keluar/create', [OutgoingGoodsController::class, 'createBarista'])->name('users.barang-keluar.create');
    Route::post('/barang-keluar', [OutgoingGoodsController::class, 'storeBarista'])->name('users.barang-keluar.store');
    Route::get('/barang-keluar/{id}', [OutgoingGoodsController::class, 'showBarista'])->name('users.barang-keluar.show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminView'])->name('admin.home');

    // Items
    Route::resource('items', ItemController::class);
    Route::post('restock-requests/{request}/status/{status}', [ItemController::class, 'updateRestockStatus'])->name('restock.update');

    // Kategori
    Route::resource('kategori', CategoryController::class);


    // Barang Masuk & Keluar
    Route::resource('barang-masuk', IncomingGoodsController::class);
    Route::resource('barang-keluar', OutgoingGoodsController::class);

    // Supplier & Customer
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan', [LaporanController::class, 'filter'])->name('laporan.filter');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');

    // Permission Barang
    Route::get('permissions', [AdminCategorySettingsController::class, 'index'])->name('admin.users.categories.index');
    Route::get('{user}/edit/permissions', [AdminCategorySettingsController::class, 'edit'])->name('admin.users.categories.edit');
    Route::put('{user}/permissions', [AdminCategorySettingsController::class, 'updatePermissions'])->name('admin.users.updatePermissions');
    
    // User LIST
    Route::get('user-auth', [AdminCategorySettingsController::class, 'index'])->name('admin.users.userauth.index');
    Route::get('{user}/edit/user-auth', [AdminCategorySettingsController::class, 'edit'])->name('admin.users.userauth.edit');
    Route::put('{user}/user-auth', [AdminCategorySettingsController::class, 'updatePermissions'])->name('admin.users.userauth'); 

    // Users Setting
     Route::resource('users', UserController::class);

});

/*
|--------------------------------------------------------------------------
| Cheff (Manager) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('cheff')->middleware(['auth', 'user-access:cheff'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'cheffView'])->name('cheff.home');
    Route::get('/items', [ItemController::class, 'indexCheff'])->name('cheff.items.index');

    // Barang Masuk
    Route::get('/barang-masuk', [IncomingGoodsController::class, 'indexCheff'])->name('cheff.barang-masuk.index');
    Route::get('/barang-masuk/create', [IncomingGoodsController::class, 'createCheff'])->name('cheff.barang-masuk.create');
    Route::post('/barang-masuk', [IncomingGoodsController::class, 'storeCheff'])->name('cheff.barang-masuk.store');
    Route::get('/barang-masuk/{id}', [IncomingGoodsController::class, 'showCheff'])->name('cheff.barang-masuk.show');

    // Barang keluar
    Route::get('/barang-keluar', [OutgoingGoodsController::class, 'indexCheff'])->name('cheff.barang-keluar.index');
    Route::get('/barang-keluar/create', [OutgoingGoodsController::class, 'createCheff'])->name('cheff.barang-keluar.create');
    Route::post('/barang-keluar', [OutgoingGoodsController::class, 'storeCheff'])->name('cheff.barang-keluar.store');
    Route::get('/barang-keluar/{id}', [OutgoingGoodsController::class, 'showCheff'])->name('cheff.barang-keluar.show');

    Route::get('/items/{item}', [ItemController::class, 'show'])->name('cheff.items.show');
    Route::post('/items/{item}/restock-request', [ItemController::class, 'requestRestock'])->name('items.restock.request');
});

