<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UpdateController;

Route::get('/', function () {
    return view('welcome');
});
Route::view('/update','update')->name('update');



    Route::post('/update-app', [UpdateController::class, 'update'])->name('app.update');

