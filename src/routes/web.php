<?php

use Illuminate\Support\Facades\Route;
use Programmeruz\LaravelCreator\Http\Controllers\ColumnController;

Route::prefix('/creator')->group( function(){
    Route::view('/', 'LaravelCreator::index')->name('index');
    Route::post('/generate', [ColumnController::class, 'generateColumns'])->name('creator.generate');
});

