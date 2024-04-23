<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/klop', function () {
    return view('welcome');
});

Route::get('/test', function() {
    return 'Hello World';
});
