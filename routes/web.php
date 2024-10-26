<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'Database migrated!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed');
    return 'Database seeded!';
});
