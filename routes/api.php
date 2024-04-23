<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/signup',[UserController::class, 'signup']);
Route::post('/users',[UserController::class, 'getUsers']);
Route::post('/test', function() {
    return 'Hello World';
});
