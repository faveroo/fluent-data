<?php 

use Gabriel\FluentData\Container\Container;
use Gabriel\FluentData\Database\Database;
use Gabriel\FluentData\Facades\Facade;
use Gabriel\FluentData\Facades\DB;

require 'vendor/autoload.php';

$app = new Container();

Facade::setContainer($app);

$app->singleton(Database::class, fn() => new Database([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'fluent_data_test',
    'username' => 'root',
    'password' => '',
]));

$users = DB::table('users')
    ->where('active', 1)
    ->get();

dd($users);
