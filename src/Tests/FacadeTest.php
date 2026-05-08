<?php

use Gabriel\FluentData\Container\Container;
use Gabriel\FluentData\Facades\Facade;
use Gabriel\FluentData\Facades\Str;

require 'vendor/autoload.php';

$app = new Container();

Facade::setContainer($app);

$mySlug = Str::slug('Teste de sistmea');
print($mySlug);


