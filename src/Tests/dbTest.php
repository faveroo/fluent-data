<?php 

use Gabriel\FluentData\Container\Container;
use Gabriel\FluentData\Database\Database;
use Gabriel\FluentData\Facades\Facade;
use Gabriel\FluentData\Facades\DB;

require 'vendor/autoload.php';

load_env(__DIR__ . '/../.env');

$app = new Container();

Facade::setContainer($app);

$config = require __DIR__ . '/../config/database.php';


$connection = $config['default'];

$app->singleton(Database::class, fn() => new Database(
    $config['connections'][$connection]
));

$users = DB::table('users')
    ->where('active', 1)
    ->get();

dd($users);
