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

use Eloquent\Typhoon\Renderer\Type as TypeRenderer;
use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Phake;

class TyphoonTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_typhoon = new Typhoon;
  }

  /**
   * @covers Eloquent\Typhoon\Typhoon::instance
   * @covers Eloquent\Typhoon\Typhoon::uninstall
   * @covers Eloquent\Typhoon\Typhoon::install
   * @group core
   */
  public function testInstanceAndInstall()
  {
    Typhoon::uninstall();

    $instance = Typhoon::instance();

    $this->assertInstanceOf('Eloquent\Typhoon\Typhoon', $instance);
    $this->assertSame($instance, Typhoon::instance());

    $instance = new Typhoon;
    $instance->install();

    $this->assertSame($instance, Typhoon::instance());
  }

  /**
   * @var Typhoon
   */
  protected $_typhoon;
}
