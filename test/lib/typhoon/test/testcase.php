<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Test;

use PHPUnit_Framework_TestCase;
use Typhoon;

class TestCase extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $typhoon = new Typhoon;
    $typhoon->install();
  }
}