<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

    Route::middleware(['auth:admin', 'verified'])->group(function () {
        Route::get('/dashboard', \Hotash\Tenancy\Controllers\Admin\DashboardController::class)->name('dashboard');
    });
});

Route::get('/', \Hotash\Tenancy\Controllers\HomeController::class);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', \Hotash\Tenancy\Controllers\DashboardController::class)->name('dashboard');
});
