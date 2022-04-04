<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('news', [NewsController::class,'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class,'show'])->name('news.show');

Route::post('news', [NewsController::class,'store'])->name('news.store');
Route::put('news/{news}', [NewsController::class,'update'])->name('news.update');
Route::delete('news/{news}', [NewsController::class,'destroy'])->name('news.destroy');
