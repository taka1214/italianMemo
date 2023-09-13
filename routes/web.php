<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index'])->name('post.index');
// Route::get('/create', [PostController::class, 'create'])->name('post.create');
Route::post('/', [PostController::class, 'store'])->name('post.store');
// Route::get('/shuffle', [PostController::class, 'shuffle'])->name('post.shuffle');
// Route::get('/shuffleReverse', [PostController::class, 'shuffleReverse'])->name('post.shuffleReverse');
Route::get('/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
Route::put('/{post}', [PostController::class, 'update'])->name('post.update');
Route::delete('/{post}', [PostController::class, 'destroy'])->name('post.destroy');

Route::get('/{any?}', function () {
  return view('index');
})->where('any', '.*');