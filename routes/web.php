<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');


Route::get('/books', [BookController::class, 'index'])->name('books');

Route::get('/books/create', [BookController::class, 'create'])->middleware('auth');
Route::post('/books', [BookController::class, 'store'])->middleware('auth');

Route::get('/search', SearchController::class);
Route::get('/tags/{tag:name}', TagController::class);  // tags/frontend


Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create']);
    Route::post('/login', [SessionController::class, 'store']);
});

Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');
