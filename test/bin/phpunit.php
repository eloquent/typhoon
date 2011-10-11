<?php

if (!isset($configFile))
{
  $configFile = 'config.xml';
}

$configPath =
    dirname(__DIR__)
    .DIRECTORY_SEPARATOR
    .'config'
    .DIRECTORY_SEPARATOR
    .$configFile
;

$command = 'phpunit --verbose --configuration '.escapeshellarg($configPath);

$arguments = $_SERVER['argv'];
array_shift($arguments);
if ($arguments) {
  $command .= ' '.implode(' ', array_map('escapeshellarg', $arguments));
}

passthru($command, $code);
exit($code);