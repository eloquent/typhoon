<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Test;

use ReflectionClass;

abstract class TypeTestCase extends TestCase
{
  /**
   * @return Type
   */
  protected function typeFixture(array $attributes = null)
  {
    $class = $this->typeClass();

    return new $class($attributes);
  }

  protected function setUp()
  {
    parent::setUp();

    $reflector = new ReflectionClass('Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType');
    $property = $reflector->getProperty('attributeSignatures');
    $property->setAccessible(true);
    $property->setValue(null, array());
  }

  /**
   * @dataProvider typeValues
   * @group types
   */
  public function testTyphoonCheck($expected, $value, $attributes = null)
  {
    $this->assertSame($expected, $this->typeFixture($attributes)->typhoonCheck($value));
  }

  /**
   * @return string
   */
  abstract protected function typeClass();

  /**
   * @return array
   */
  abstract public function typeValues();
}
