<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ThreadController::class, 'index'])->name('home');
Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/thread/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::get('/thread/{thread}/topic/{topic}', [TopicController::class, 'show'])->name('topics.show')->scopeBindings();

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/thread/{thread}/topic/{topic}/reply', [ReplyController::class, 'store'])->name('replies.store')->scopeBindings();
});

require __DIR__.'/auth.php';
