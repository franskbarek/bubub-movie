<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\FavoriteController;

// Language switch
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');
    Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
    Route::get('/movies/detail-json', [MovieController::class, 'detailJson'])->name('movies.detail-json');
    Route::get('/movies/autocomplete', [MovieController::class, 'autocomplete'])->name('movies.autocomplete');
    Route::get('/movies/{imdbID}', [MovieController::class, 'show'])->name('movies.show');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{imdbID}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/favorites/check/{imdbID}', [FavoriteController::class, 'check'])->name('favorites.check');
});
