<?php

use Illuminate\Support\Facades\Route;
use Programmeruz\LaravelCreator\Http\Controllers\ColumnController;
use Programmeruz\LaravelCreator\Http\Controllers\BladeController;
use Programmeruz\LaravelCreator\Http\Controllers\PageController;

Route::prefix('/creator')->group( function(){
//    Route::view('/', 'LaravelCreator::index')->name('index');
    Route::get('/', [PageController::class, 'getIndex'])->name('index');
    Route::post('/generate', [ColumnController::class, 'generateColumns'])->name('creator.generate');
});
