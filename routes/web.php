<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController as I;
use App\Http\Controllers\TagController as T;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::name('ideas-')->group(function () {
    Route::get('/list', [I::class, 'index'])->name('index');
    Route::get('/create', [I::class, 'create'])->name('create');
    Route::post('/create', [I::class, 'store'])->name('store');
    Route::get('/{idea}', [I::class, 'show'])->name('show');
    Route::get('/edit/{idea}', [I::class, 'edit'])->name('edit');
    Route::put('/edit/{idea}', [I::class, 'update'])->name('update');
    Route::put('/pledge/{idea}', [I::class, 'pledge'])->name('pledge');
    Route::put('/like/{idea}', [I::class, 'like'])->name('like');
    Route::get('/confirm/{idea}', [I::class, 'confirm'])->name('confirm');
    Route::delete('/delete/{idea}', [I::class, 'destroy'])->name('delete');
});

Route::prefix('tag')->name('tags-')->group(function () {
    Route::get('/list', [T::class, 'index'])->name('index');
    Route::get('/create', [T::class, 'create'])->name('create');
    Route::post('/create', [T::class, 'store'])->name('store');
    Route::get('/edit/{tag}', [T::class, 'edit'])->name('edit');
    Route::put('/edit/{tag}', [T::class, 'update'])->name('update');
    Route::delete('/delete/{tag}', [T::class, 'destroy'])->name('delete');
});