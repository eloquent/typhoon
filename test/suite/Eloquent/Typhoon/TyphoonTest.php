<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon;

use Phake;

/**
 * @covers Eloquent\Typhoon\Typhoon
 * @group core
 */
class TyphoonTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon($this->_typeRegistry);
  }

  public function testInstanceAndInstall()
  {
    Typhoon::uninstall();

    $instance = Typhoon::instance();

    $this->assertInstanceOf('Eloquent\Typhoon\Typhoon', $instance);
    $this->assertSame($instance, Typhoon::instance());

    $instance = new Typhoon;
    Typhoon::install($instance);

    $this->assertSame($instance, Typhoon::instance());
  }
}
