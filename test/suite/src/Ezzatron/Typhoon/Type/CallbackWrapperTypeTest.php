<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Attribute\Attributes;

class CallbackWrapperTypeTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Type\CallbackWrapperType
   */
  public function testCallbackWrapperType()
  {
    $called = false;
    $arguments = null;
    
    $callback = function() use(&$called, &$arguments)
    {
      $called = true;
      $arguments = func_get_args();
    };
    $attributes = new Attributes(array(
      CallbackWrapperType::ATTRIBUTE_CALLBACK => $callback,
      CallbackWrapperType::ATTRIBUTE_ARGUMENTS => array('bar', 'baz'),
    ));
    $type = new CallbackWrapperType($attributes);
    
    $this->assertFalse($called);
    $this->assertNull($arguments);

    $type->typhoonCheck('foo');

    $this->assertTrue($called);
    $this->assertEquals(array('foo', 'bar', 'baz'), $arguments);
  }
}