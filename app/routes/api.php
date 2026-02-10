<?php

use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SerieController;
use Illuminate\Support\Facades\Route;

Route::get('/movies', [MovieController::class, 'index'])->name('movie.index');
Route::get('/series', [SerieController::class, 'index'])->name('serie.index');
Route::get('/genres', [GenreController::class, 'index'])->name('genre.index');
