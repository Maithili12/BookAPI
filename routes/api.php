<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])
    ->group(function () {
        Route::get('books/search', [BookController::class, 'search'])->name('books.search');
        Route::resource('books', BookController::class)->except(['create', 'edit']);
    });

