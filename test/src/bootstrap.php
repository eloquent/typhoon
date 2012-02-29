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

// path constants
require __DIR__.'/paths.php';

// include Phake for improved mocking support
require 'Phake.php';
Phake::setClient(Phake::CLIENT_PHPUNIT);

// include Typhax
require TYPHOON_VENDOR_DIR.DIRECTORY_SEPARATOR.'eloquent'.DIRECTORY_SEPARATOR.'typhax'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'include.php';

// include Typhoon
require TYPHOON_SRC_DIR.DIRECTORY_SEPARATOR.'include.php';

// include test fixtures
require TYPHOON_TEST_SRC_DIR.DIRECTORY_SEPARATOR.'include.php';
