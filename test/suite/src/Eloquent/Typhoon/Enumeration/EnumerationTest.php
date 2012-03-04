<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Enumeration;

use Eloquent\Typhoon\Test\Fixture\Enumeration as EnumerationFixture;

class EnumerationTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Enumeration\Enumeration::values
   * @covers Eloquent\Typhoon\Enumeration\Enumeration::_values
   * @group Enumeration
   */
  public function testValues()
  {
    $expected = array(
      'FOO' => 'bar',
      'BAR' => 'baz',
      'BAZ' => 'qux',
    );

    $this->assertSame($expected, EnumerationFixture::values());
  }
  
  /**
   * @covers Eloquent\Typhoon\Enumeration\Enumeration::values
   * @group Enumeration
   */
  public function testValuesFailure()
  {
    $this->setExpectedException('Eloquent\Typhoon\Exception\UndefinedMethodException');
    Enumeration::values();
  }
}
