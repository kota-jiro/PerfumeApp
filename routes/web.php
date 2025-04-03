<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Product\AdminProductController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'indexAll'])->name('products.index');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/filter', [ProductController::class, 'filterByCategory'])->name('products.filter');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('admin/products/save', [AdminProductController::class, 'save'])->name('admin.products.save');
    Route::get('admin/products/edit/{id}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('admin/products/update/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/delete/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.delete');

    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users/save', [UserController::class, 'save'])->name('admin.users.save');
    Route::get('admin/users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('admin/users/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users/updateRole', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
});

require __DIR__ . '/auth.php';

// Route::get('admin/dashboard', [HomeController::class, 'index']);
// Route::get('admin/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');