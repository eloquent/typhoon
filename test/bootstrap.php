<?php

$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Eloquent\Typhoon\TestFixture', __DIR__.'/src');
$autoloader->add('Typhoon\Eloquent\Typhoon\TestFixture', __DIR__.'/src');

Phake::setClient(Phake::CLIENT_PHPUNIT);
