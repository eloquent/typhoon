<?php

use Typhoon\Typhoon;

$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Eloquent\Typhoon\TestFixture', __DIR__.'/src');
$autoloader->add('Typhoon\Eloquent\Typhoon\TestFixture', __DIR__.'/src');

Typhoon::setRuntimeGeneration(true);
Phake::setClient(Phake::CLIENT_PHPUNIT);
