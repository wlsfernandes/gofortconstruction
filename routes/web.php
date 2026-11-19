<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::view('/', 'admin.dashboard')->name('dashboard');
});
