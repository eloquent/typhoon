<?php

$autoloader->add('Typhoon', __DIR__.'/../src-deploy');
$autoloader->add('Eloquent\Typhoon\TestCase', __DIR__.'/src');
$autoloader->add('Eloquent\Typhoon\TestFixture', __DIR__.'/src');
$autoloader->add('Typhoon\Validator\Eloquent\Typhoon\TestFixture', __DIR__.'/src');
$autoloader->register();
