<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Catch-all route for Vue Router
//Route::view('/{any}', 'welcome')->where('any', '.*');
Route::view('/{any}', 'welcome')->where('any', '^(?!api/).*$');
 