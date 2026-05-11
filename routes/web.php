<?php

// php -S localhost:8000 -t public

use Gabriel\FluentData\DTO\UserData;
use Gabriel\FluentData\Facades\DB;
use Gabriel\FluentData\Facades\Route;

Route::get('/', function () {
    $users = DB::table('users')->get()
        ->map(fn (array $user) => UserData::fromArray($user));

    return $users->toJson();

});

Route::get('/users', function () {
    return 'Users';
});
