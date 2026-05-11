<?php

// php -S localhost:8000 -t public

use Gabriel\FluentData\Facades\DB;
use Gabriel\FluentData\Facades\Route;

Route::get('/', function () {
    return DB::table('users')->get();
});

Route::get('/users', function () {
    return 'Users';
});