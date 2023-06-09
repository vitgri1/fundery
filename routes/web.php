<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController as I;
use App\Http\Controllers\TagController as T;
use App\Http\Controllers\FrontController as F;

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

Auth::routes();

Route::redirect('/','/home');

// fff
Route::name('front-')->group(function () {
    Route::get('/home', [F::class, 'index'])->name('index');
    Route::get('/idea/{idea}', [F::class, 'show'])->name('show');
    Route::get('/create', [I::class, 'create'])->name('create');
    Route::post('/create', [I::class, 'store'])->name('store')->middleware('role:admin|client');
    Route::get('/edit', [I::class, 'edit'])->name('edit')->middleware('role:admin|client');
    Route::put('/edit/{idea}', [I::class, 'update'])->name('update')->middleware('role:admin|client');
    Route::get('/tags-list', [I::class, 'getTagsList'])->name('tags-list');
    Route::put('/add-tag', [I::class, 'addTag'])->name('add-tag');
    // cia 
    Route::put('/delete-tag', [I::class, 'deleteTag'])->name('delete-tag');
        // ^^^^^^
    Route::post('/add-new-tag', [I::class, 'addNewTag'])->name('add-new-tag');
    Route::put('/like/{idea}', [I::class, 'like'])->name('like')->middleware('role:admin|client');
    Route::put('/pledge/{idea}', [I::class, 'pledge'])->name('pledge')->middleware('role:admin|client');
    Route::get('/idea-gallery/{idea}', [I::class, 'gallery'])->name('gallery');
});

//  added admin \||/  <==
Route::prefix('admin')->name('admin-')->group(function () {
    Route::get('/list', [I::class, 'index'])->name('index')->middleware('role:admin');

    Route::get('/{idea}', [I::class, 'show'])->name('show')->middleware('role:admin');

    Route::get('/confirm/{idea}', [I::class, 'confirm'])->name('confirm')->middleware('role:admin|client');
    Route::delete('/delete/{idea}', [I::class, 'destroy'])->name('delete')->middleware('role:admin|client');
});

Route::prefix('tag')->name('tags-')->group(function () {
    Route::get('/list', [T::class, 'index'])->name('index')->middleware('role:admin|client');
    Route::get('/create', [T::class, 'create'])->name('create')->middleware('role:admin|client');
    Route::post('/create', [T::class, 'store'])->name('store')->middleware('role:admin|client');
    Route::get('/edit/{tag}', [T::class, 'edit'])->name('edit')->middleware('role:admin|client');
    Route::put('/edit/{tag}', [T::class, 'update'])->name('update')->middleware('role:admin|client');
    Route::delete('/delete/{tag}', [T::class, 'destroy'])->name('delete')->middleware('role:admin|client');
});