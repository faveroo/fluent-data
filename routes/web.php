<?php

// php -S localhost:8000 -t public

use Gabriel\FluentData\Facades\Route;

Route::get('/', function () {
    return 'Home';
});

Route::get('/users', function () {
    return 'Users';
});