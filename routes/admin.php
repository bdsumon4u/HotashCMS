<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the AuthProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', RouteServiceProvider::HOME);
Route::redirect('/register', RouteServiceProvider::HOME);

Route::middleware(['auth:admin', 'verified'])->group(function () {
    Route::get('/dashboard', \Hotash\Admin\Controllers\DashboardController::class)->name('dashboard');
});
