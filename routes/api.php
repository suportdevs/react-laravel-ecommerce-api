<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [RegisteredUserController::class, 'register'])->name('register');
Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login');

Route::get('/email/verify/{id}/{hash}', [RegisteredUserController::class, 'verifyEmail'])
    // ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::get('/send/email/verify-link/{id}', [RegisteredUserController::class, 'sendVerifyEmail'])
    ->name('send.verification.mail');
    
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
->name('password.store');
// Products routes
Route::post('/products', [ProductController::class, 'products'])->name('products');
Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');
Route::post('/categories', [CategoryController::class, 'categories'])->name('categories');
Route::get('/sizes', [SizeController::class, 'sizes'])->name('sizes');
Route::get('/colors', [ColorController::class, 'colors'])->name('colors');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/delete', [ProductController::class, 'delete'])->name('products.delete');

    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/{id}/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/delete', [CategoryController::class, 'delete'])->name('categories.delete');
    
    Route::post('/sizes/store', [SizeController::class, 'store'])->name('sizes.store');
    Route::post('/sizes/{id}/update', [SizeController::class, 'update'])->name('sizes.update');
    Route::post('/sizes/delete', [SizeController::class, 'delete'])->name('sizes.delete');

    Route::post('/colors/store', [ColorController::class, 'store'])->name('colors.store');
    Route::post('/colors/{id}/update', [ColorController::class, 'update'])->name('colors.update');
    Route::post('/colors/delete', [ColorController::class, 'delete'])->name('colors.delete');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
