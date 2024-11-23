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
    Route::get('/auctions/search', 'search')->name('search.results');
    Route::get('/auctions/{auction}', 'show')->name('auction.show');
    Route::get('/auction/{auction}/edit', 'edit')->name('auction.edit');
    Route::post('/auction/{auction}/edit', 'update')->name('auction.update');
    Route::get('/auction/{auction}/cancel', 'cancel')->name('auction.cancel');
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
    Route::get('/user/{user}', 'show')->name('user.show');
    Route::get('/user/{user}/won-auctions', [UserController::class, 'showWonAuctions'])->name('user.wonAuctions');
    Route::get('/user/{user}/bids', [UserController::class, 'showBids'])->name('user.bids');
    Route::get('/user/{user}/auctions', [UserController::class, 'showAuctions'])->name('user.auctions');
    Route::post('/user/{user}/destroy', 'destroy')->name('user.destroy');
    Route::get('/user/{user}/edit', 'edit')->name('user.edit'); //edit form
    Route::post('/user/{user}', 'update')->name('user.update');
    Route::get('/admin/users', 'index')->name('user.index');
    Route::get('/admin/users/create', 'create')->name('user.create');
    Route::post('/admin/users/create', 'storeUser')->name('user.store');
});

Route::controller(BidController::class)->group(function () {
    Route::post('/auctions/{auction}/bids', 'store')->name('auctions.bids.store');
    Route::get('/auction/{auction}/bids', 'index')->name('auctions.bids.index');
});



