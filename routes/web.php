<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Ajax routes
Route::group(['namespace' => 'App\Http\Controllers\Ajax', 'prefix' => 'ajax'], function () {
    Route::post('/url/create', 'UrlController@create')->name('ajax.url.create');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes
require __DIR__.'/auth.php';

// Catch-all route, used for short urls
Route::fallback([UrlController::class, 'redirect']);
