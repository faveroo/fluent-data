<?php

use Gabriel\FluentData\Container\Container;
use Gabriel\FluentData\Database\Database;
use Gabriel\FluentData\Facades\Facade;
use Gabriel\FluentData\Routing\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

load_env(dirname(__DIR__) . '/src/.env');

$app = new Container();
$app->singleton(Router::class, Router::class);

$config = require dirname(__DIR__) . '/src/config/database.php';
$connection = $config['default'];

$app->singleton(Database::class, fn() => new Database(
    $config['connections'][$connection]
));

Facade::setContainer($app);

require dirname(__DIR__) . '/routes/web.php';

$response = $app->make(Router::class)->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
    );

print_r($response);
