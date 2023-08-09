<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/creator')->group( function(){
    Route::view('/', 'LaravelCreator::index')->name('index');
    Route::post('/generate', [\App\Http\Controllers\ColumnController::class, 'generateColumns'])->name('creator.generate');
});

