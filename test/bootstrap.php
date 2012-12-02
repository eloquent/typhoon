<?php

use Typhoon\Typhoon;

$autoloader = require __DIR__.'/../vendor/autoload.php';
$autoloader->add('Eloquent\Typhoon\TestCase', __DIR__.'/src');
$autoloader->add('Eloquent\Typhoon\TestFixture', __DIR__.'/src');
$autoloader->add('Typhoon\Validator\Eloquent\Typhoon\TestFixture', __DIR__.'/src');

Eloquent\Asplode\Asplode::instance()->install();
Phake::setClient(Phake::CLIENT_PHPUNIT);
