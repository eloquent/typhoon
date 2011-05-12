<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

error_reporting(E_ALL | E_STRICT | E_DEPRECATED);

if (!defined('TYPHOON_TEST_COVERAGE_DIR'))
{
  define('TYPHOON_TEST_COVERAGE_DIR', dirname(__DIR__).DIRECTORY_SEPARATOR.'coverage');
}

// spl_autoload default implementation SHOULD do this itself, but it does not work for me
spl_autoload_register(function($name)
{
  include str_replace('\\', DIRECTORY_SEPARATOR, strtolower($name)).'.php';
});

// clean coverage reports
typhoon_test_delete_recursive(TYPHOON_TEST_COVERAGE_DIR.DIRECTORY_SEPARATOR.'report');
typhoon_test_delete_recursive(TYPHOON_TEST_COVERAGE_DIR.DIRECTORY_SEPARATOR.'coverage.xml');

function typhoon_test_delete_recursive($path)
{
  if (is_dir($path))
  {
    array_map('typhoon_test_delete_recursive', glob($path.DIRECTORY_SEPARATOR.'*'));
    rmdir($path);
  }
  elseif (is_file($path))
  {
    unlink($path);
  }
}