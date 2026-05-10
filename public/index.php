<?php

use Gabriel\FluentData\Container\Container;
use Gabriel\FluentData\Facades\Facade;
use Gabriel\FluentData\Routing\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new Container();
$app->singleton(Router::class, Router::class);

Facade::setContainer($app);

require dirname(__DIR__) . '/routes/web.php';

// print_r($_SERVER['REQUEST_METHOD']);
// print_r($_SERVER['REQUEST_URI']);

$response = $app->make(Router::class)->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
    );

echo $response;
