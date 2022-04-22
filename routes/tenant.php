<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::domain('admin.{domain}')->as('admin.')->group(function () {
    Route::redirect('/', RouteServiceProvider::HOME);
    Route::redirect('/register', RouteServiceProvider::HOME);

    Route::middleware(['auth:admin', 'verified', \Hotash\Tenancy\Middleware\IgnoreDomainParameter::class])->group(function () {
        Route::get('/dashboard', \Hotash\Tenancy\Controllers\Admin\DashboardController::class)->name('dashboard');
        \Hotash\DataTable\InertiaTable::route(\App\Table\Tenant\Admin\BrandTable::class);
        Route::resource('/brands', \App\Http\Controllers\Tenant\Admin\BrandController::class);
        \Hotash\DataTable\InertiaTable::route(\App\Table\Tenant\Admin\CategoryTable::class);
        Route::resource('/categories', \App\Http\Controllers\Tenant\Admin\CategoryController::class);
        \Hotash\DataTable\InertiaTable::route(\App\Table\Tenant\Admin\AttributeTable::class);
        Route::resource('/attributes', \App\Http\Controllers\Tenant\Admin\AttributeController::class);
        \Hotash\DataTable\InertiaTable::route(\App\Table\Tenant\Admin\ProductTable::class);
        Route::resource('/products', \App\Http\Controllers\Tenant\Admin\ProductController::class);

        Route::get('/media', \App\Http\Controllers\Tenant\Admin\MediaController::class)->name('media');
//        Route::post('/media/{folder}', [\App\Http\Controllers\Tenant\Admin\MediaController::class, 'upload'])->name('media.store');
    });
});

Route::get('/', \Hotash\Tenancy\Controllers\HomeController::class);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', \Hotash\Tenancy\Controllers\DashboardController::class)->name('dashboard');
});
