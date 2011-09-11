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

if (!defined('TYPHOON_ROOT_DIR')) define('TYPHOON_ROOT_DIR', dirname(dirname(__DIR__)));
if (!defined('TYPHOON_SRC_DIR')) define('TYPHOON_SRC_DIR', TYPHOON_ROOT_DIR.DIRECTORY_SEPARATOR.'src');
if (!defined('TYPHOON_TEST_DIR')) define('TYPHOON_TEST_DIR', TYPHOON_ROOT_DIR.DIRECTORY_SEPARATOR.'test');
if (!defined('TYPHOON_TEST_SRC_DIR')) define('TYPHOON_TEST_SRC_DIR', TYPHOON_TEST_DIR.DIRECTORY_SEPARATOR.'src');
if (!defined('TYPHOON_TEST_SUITE_DIR')) define('TYPHOON_TEST_SUITE_DIR', TYPHOON_TEST_DIR.DIRECTORY_SEPARATOR.'suite');
if (!defined('TYPHOON_TEST_REPORT_DIR')) define('TYPHOON_TEST_REPORT_DIR', TYPHOON_TEST_DIR.DIRECTORY_SEPARATOR.'report');

// include Phake for improved mocking support
require 'Phake.php';

// include Typhoon
require TYPHOON_SRC_DIR.DIRECTORY_SEPARATOR.'include.php';

// include test fixtures
require TYPHOON_TEST_SRC_DIR.DIRECTORY_SEPARATOR.'include.php';

// clean reports
foreach(glob(TYPHOON_TEST_REPORT_DIR.DIRECTORY_SEPARATOR.'*') as $report)
{
  typhoon_test_delete_recursive($report);
}

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