<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PostController::class, 'index'])->name('post.index');
Route::get('/create', [PostController::class, 'create'])->name('post.create');
Route::post('/', [PostController::class, 'store'])->name('post.store');
Route::get('shuffle', [PostController::class, 'shuffle'])->name('post.shuffle');
Route::get('/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
Route::put('/{post}', [PostController::class, 'update'])->name('post.update');
Route::delete('/{post}', [PostController::class, 'destroy'])->name('post.destroy');