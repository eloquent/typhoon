<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use Typhoon\Test\TestCase;

class CallbackWrapperTest extends TestCase
{
  /**
   * @covers Typhoon\Type\CallbackWrapper::check
   */
  public function testCheck()
  {
    $called = false;
    $arguments = null;
    $type = new CallbackWrapper;
    $type->setCallback(function() use(&$called, &$arguments)
    {
      $called = true;
      $arguments = func_get_args();
    });
    $type->setArguments(array('bar', 'baz'));
    
    $this->assertFalse($called);
    $this->assertNull($arguments);

    $type->check('foo');

    $this->assertTrue($called);
    $this->assertEquals(array('foo', 'bar', 'baz'), $arguments);
  }

  /**
   * @covers Typhoon\Type\CallbackWrapper::setCallback
   * @covers Typhoon\Type\CallbackWrapper::callback
   */
  public function testCallback()
  {
    $type = new CallbackWrapper;

    $this->assertEquals(function() { return true; }, $type->callback());

    $callback = function($value) { return false; };
    $type->setCallback($callback);

    $this->assertSame($callback, $type->callback());
  }

  /**
   * @covers Typhoon\Type\CallbackWrapper::setArguments
   * @covers Typhoon\Type\CallbackWrapper::arguments
   */
  public function testArguments()
  {
    $type = new CallbackWrapper;

    $this->assertEquals(array(), $type->arguments());

    $arguments = array('foo', 'bar');
    $type->setArguments($arguments);

    $this->assertSame($arguments, $type->arguments());
  }
}