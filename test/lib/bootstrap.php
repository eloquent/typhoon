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
if (!defined('TYPHOON_DEPLOY_DIR')) define('TYPHOON_DEPLOY_DIR', TYPHOON_ROOT_DIR.DIRECTORY_SEPARATOR.'deploy');
if (!defined('TYPHOON_LIB_DIR')) define('TYPHOON_LIB_DIR', TYPHOON_DEPLOY_DIR.DIRECTORY_SEPARATOR.'lib');
if (!defined('TYPHOON_TEST_DIR')) define('TYPHOON_TEST_DIR', TYPHOON_ROOT_DIR.DIRECTORY_SEPARATOR.'test');
if (!defined('TYPHOON_TEST_LIB_DIR')) define('TYPHOON_TEST_LIB_DIR', TYPHOON_TEST_DIR.DIRECTORY_SEPARATOR.'lib');
if (!defined('TYPHOON_TEST_SUITE_DIR')) define('TYPHOON_TEST_SUITE_DIR', TYPHOON_TEST_DIR.DIRECTORY_SEPARATOR.'suite');
if (!defined('TYPHOON_TEST_REPORT_DIR')) define('TYPHOON_TEST_REPORT_DIR', TYPHOON_TEST_DIR.DIRECTORY_SEPARATOR.'report');

if (!defined('TYPHOON_INCLUDE_PATH_SET'))
{
  define('TYPHOON_INCLUDE_PATH_SET', true);

  set_include_path(
    get_include_path()
    .PATH_SEPARATOR.TYPHOON_LIB_DIR
    .PATH_SEPARATOR.TYPHOON_TEST_LIB_DIR
  );
}

// spl_autoload default implementation SHOULD do this itself, but it does not work for me
spl_autoload_register(function($name)
{
  include str_replace('\\', DIRECTORY_SEPARATOR, strtolower($name)).'.php';
});

// clean reports
foreach(glob(TYPHOON_TEST_REPORT_DIR.DIRECTORY_SEPARATOR.'*') as $report)
{
  typhoon_test_delete_recursive($report);
}

// include Phake for improved mocking support
if (!defined('TYPHOON_PHAKE_SRC_DIR'))
{
  define('TYPHOON_PHAKE_SRC_DIR', __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'phake'.DIRECTORY_SEPARATOR.'src');
}
set_include_path(TYPHOON_PHAKE_SRC_DIR.PATH_SEPARATOR.  get_include_path());
require_once TYPHOON_PHAKE_SRC_DIR.DIRECTORY_SEPARATOR.'Phake.php';

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