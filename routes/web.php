<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', HomeController::class)->name('home');
Route::get('/details/{blog}', DetailController::class)->name('detail-page');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::resource('blogs', BlogController::class);
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');

    Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('blogs/{blog}/update-status', [BlogController::class, 'updateStatus'])->name('blogs.status.update');
});

require __DIR__.'/auth.php';
