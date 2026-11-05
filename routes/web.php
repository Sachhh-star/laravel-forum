<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Voor iedereen zichtbaar, ook zonder in te loggen
Route::get('/', [ThreadController::class, 'index'])->name('home');
Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');

// Breeze stuurt na het inloggen naar dashboard: dat is bij ons de startpagina
Route::redirect('/dashboard', '/')->name('dashboard');

// Alleen voor een administrator
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::delete('/thread/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');
    Route::delete('/thread/{thread}/topic/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy')->scopeBindings();
    Route::delete('/thread/{thread}/topic/{topic}/reply/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy')->scopeBindings();

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Alleen voor ingelogde gebruikers
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/thread/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::patch('/thread/{thread}', [ThreadController::class, 'update'])->name('threads.update');

    Route::post('/thread/{thread}/topic', [TopicController::class, 'store'])->name('topics.store');
    Route::get('/thread/{thread}/topic/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit')->scopeBindings();
    Route::patch('/thread/{thread}/topic/{topic}', [TopicController::class, 'update'])->name('topics.update')->scopeBindings();

    Route::post('/thread/{thread}/topic/{topic}/reply', [ReplyController::class, 'store'])->name('replies.store')->scopeBindings();
    Route::get('/thread/{thread}/topic/{topic}/reply/{reply}/edit', [ReplyController::class, 'edit'])->name('replies.edit')->scopeBindings();
    Route::patch('/thread/{thread}/topic/{topic}/reply/{reply}', [ReplyController::class, 'update'])->name('replies.update')->scopeBindings();
});

Route::get('/thread/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::get('/thread/{thread}/topic/{topic}', [TopicController::class, 'show'])->name('topics.show')->scopeBindings();

require __DIR__.'/auth.php';
