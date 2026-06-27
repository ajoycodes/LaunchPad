<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpvoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/upvote/{product}', [UpvoteController::class, 'toggle'])->name('upvote.toggle');
    Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/updates', [DashboardController::class, 'storeUpdate'])->name('dashboard.updates.store');
});

Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/makers/{username}', [MakerController::class, 'show'])->name('makers.show');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])->name('collections.show');

Route::middleware('auth')->group(function () {
    Route::get('/collections/new', [CollectionController::class, 'create'])->name('collections.create');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::post('/collections/{collection}/add-product', [CollectionController::class, 'addProduct'])->name('collections.add-product');
    Route::post('/collections/{collection}/follow', [CollectionController::class, 'follow'])->name('collections.follow');
    Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');
});

Route::middleware(['auth', 'maker'])->group(function () {
    Route::get('/submit', [ProductController::class, 'create'])->name('products.create');
    Route::post('/submit', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product:slug}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product:slug}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product:slug}', [ProductController::class, 'destroy'])->name('products.destroy');
});
