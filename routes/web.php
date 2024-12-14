<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MiscController;
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

// Auctions
Route::controller(AuctionController::class)->group(function () {
    Route::get('/auction', 'index')->name('auctions.index');
    Route::get('/auction/create', 'create')->name('auctions.create');
    Route::post('/auction/store', 'store')->name('auction.store');
    Route::get('/auction/search', 'search')->name('search.results');
    Route::get('/auction/{auction}', 'show')->name('auction.show');
    Route::get('/auction/{auction}/edit', 'edit')->name('auction.edit');
    Route::post('/auction/{auction}/edit', 'update')->name('auction.update');
    Route::get('/auction/{auction}/cancel', 'cancel')->name('auction.cancel');
    Route::post('/auction/{auction}/cancel', 'cancel')->name('auction.cancel');
    Route::post('/auction/{auction}/follow', 'follow')->name('auction.follow');
    Route::post('/auction/{auction}/unfollow', 'unfollow')->name('auction.unfollow');
    Route::get('/admin/auctions', 'adminIndex')->name('admin.auctions');
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
    Route::get('/user/{user}/followed', 'showFollowed')->name('user.followed');
    Route::get('/user/{user}/balance', 'showBalance')->name('user.balance');
    Route::post('/user/{user}/deposit', 'deposit')->name('user.deposit');
    Route::get('/admin', 'admin_index')->name('admin.index');
    Route::get('/admin/users', 'index')->name('user.index');
    Route::get('/admin/users/create', 'create')->name('user.create');
    Route::post('/admin/users/create', 'storeUser')->name('user.store');
    Route::post('/admin/users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::post('/admin/users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
});

Route::controller(BidController::class)->group(function () {
    Route::post('/auction/{auction}/bids', 'store')->name('auctions.bids.store');
    Route::post('/auction/{auction}/bids/{bid}/withdraw', [BidController::class, 'withdraw'])->name('bids.withdraw');
    Route::get('/auction/{auction}/bids', 'index')->name('auctions.bids.index');
});

Route::controller(CategoryController::class)->group(function () {
   Route::get('/admin/categories', 'index')->name('categories.index');
   Route::get('/admin/categories/create', 'create')->name('categories.create');
   Route::post('/admin/categories/create', 'store')->name('categories.store');
});

Route::controller(ReportController::class)->group(function () {
    Route::post('/admin/reports/{report}/discard', 'discard')->name('admin.reports.discard');
    Route::post('/admin/reports/{report}/process', 'process')->name('admin.reports.process');
    Route::get('/report/create/{auction}', 'create')->name('report.create');
    Route::post('/report/store', 'store')->name('report.store');
    Route::get('/admin/reports', 'index')->name('admin.reports');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/password/reset', 'showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group( function () {
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('/password/reset', 'reset')->name('password.update');
});

Route::controller(MiscController::class)->group(function () {
    Route::get('/about', 'about')->name('misc.about');
    Route::get('/features', [MiscController::class, 'features'])->name('features');
    Route::get('/faq', 'faq')->name('faq');
});
