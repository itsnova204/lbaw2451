<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/login');

// Cards
Route::controller(AuctionController::class)->group(function () {
    Route::get('/auctions', 'index')->name('auctions.index');
    Route::get('/auction/create', 'create')->name('auctions.create');
    Route::post('/auction/store', 'store')->name('auction.store');
    Route::get('/auction/{auction}', 'show')->name('auction.show');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/admin/users', 'index')->name('user.index');
    Route::get('/user/{user}', 'show')->name('user.show');
    Route::post('/user/{user}', 'destroy')->name('user.destroy');
    Route::get('/admin/users/create', 'create')->name('user.create');
    Route::post('/admin/users/create', 'storeUser')->name('user.store');
});

// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search')->name('search.results');
});

Route::controller(BidController::class)->group(function () {
    Route::post('/auctions/{auction}/bids', 'store')->name('auctions.bids.store');
    Route::get('/auction/{auction}/bids', 'index')->name('auctions.bids.index');
});



