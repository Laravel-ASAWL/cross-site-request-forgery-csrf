<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('/', [CommentController::class, 'index'])->name('home');
Route::post('/', [CommentController::class, 'store'])->name('comment');
