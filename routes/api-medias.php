<?php

use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::post('upload/{collection?}', [MediaController::class, 'store'])->name('medias.store');
Route::post('upload/multiple/files', [MediaController::class, 'uploadMultipleFiles'])->name('medias.upload_multiple_files');

Route::delete('upload', [MediaController::class, 'destroy'])->name('medias.destroy');
