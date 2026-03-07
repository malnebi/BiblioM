<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LoanController;
use App\Models\Library;
use Termwind\Components\Li;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/tags', [TagController::class, 'tags']);
Route::get('/search-tag', [TagController::class, 'searchTag'])->name('search-tag');


Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/about-app', [PageController::class, 'aboutApp'])->name('about');

/** Библиотеке */
Route::get('/libraries/{id}', [LibraryController::class, 'show']);
Route::get('/library/libraryBooks/{id}', [LibraryController::class, 'libraryBooks'])->middleware('auth'); // sve knjige jedne biblioteke


/** Члан */
Route::get('/users', [UserController::class, 'index']); // Навигацијски линк за приказ странице са члановима и њиховим библиотекама
Route::get('/users/{id}', [UserController::class, 'show']);   // профил корисника - члана 
Route::get('/loggedUser/{id}', [UserController::class, 'showLoggedUser'])->middleware('auth'); // профил улогованог корисника    
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->middleware('auth');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth');



/** Позајмица */ 
Route::get('/loans', [LoanController::class, 'index'])->middleware('auth');
Route::get('/loans/myLibraryLoans/{id}', [LoanController::class, 'myLibraryLoans'])->middleware('auth'); // приказ позајмица библиотеке улогованог корисника
Route::get('/loans/create/{userId}', [LoanController::class, 'create'])->middleware('auth');
Route::post('/loans', [LoanController::class, 'store'])->middleware('auth'); // креирање позајмице - библиотека додјељује позајмицу члану

Route::put('/loans/loanBookMemberConfirm/{loan}', [LoanController::class, 'loanBookMemberConfirm'])->middleware('auth'); // члан потврђује позајмицу
Route::put('/loans/loanLibraryConfirm/{loan}', [LoanController::class, 'loanLibraryConfirm'])->middleware('auth'); // библиотека потврђује позајмицу

Route::put('/loans/{loan}', [LoanController::class, 'update'])->middleware('auth');   //vraćanje knjige
Route::put('/loans/returnBookLibraryConfirm/{loan}', [LoanController::class, 'returnBookLibraryConfirm'])->middleware('auth'); // библиотека потврђује враћање књиге

Route::put('/loans/extend/{loan}', [LoanController::class, 'updateReturnDate'])->middleware('auth');  // produžavanje roka za vraćanje knjige




/** BOOK */

//Route::get('/books', [BookController::class, 'index'])->name('books');
Route::resource('books', BookController::class);
Route::get('/books/create', [BookController::class, 'create'])->middleware('auth');
Route::post('/books', [BookController::class, 'store'])->middleware('auth');
Route::get('/books/{book}/book', [BookController::class, 'show']); 
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->middleware('auth');
Route::put('/books/{book}', [BookController::class, 'update'])->middleware('auth');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->middleware('auth');

Route::post('/bookReservation/{book}', [BookController::class, 'bookReservation'])->middleware('auth'); // креирање позајмице - члан резервише књигу 


Route::get('/search', SearchController::class);
Route::get('/tags/{tag:name}', TagController::class);  // tags/fantasy




Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');