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

use Phake;
use ReflectionObject;
use stdClass;
use Typhoon\OrType;
use Typhoon\Test\TypeTestCase;

class TraversableTest extends TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(false, null),                             // #0: null
      array(false,  true),                            // #1: boolean
      array(false, 'string'),                         // #2: string
      array(false, 1),                                // #3: integer
      array(false, .1),                               // #4: float
      array(true, array()),                           // #5: array
      array(false, new stdClass),                     // #6: object
      array(false, function(){}),                     // #7: closure
      array(false, $this->resourceFixture()),         // #8: resource

      array(true, Phake::mock('Iterator')),           // #9: iterator
      array(true, Phake::mock('IteratorAggregate')),  // #9: iterator aggregate
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\Traversable';
  }

  /**
   * @covers Typhoon\Type\Traversable::__construct
   * @covers Typhoon\Type\Traversable::primaryType
   */
  public function testPrimaryType()
  {
    $traversableObject = new Object;
    $traversableObject->typhoonAttributes()->set(Object::ATTRIBUTE_CLASS, 'Traversable');
    $expected = new OrType;
    $expected->addTyphoonType(new ArrayType);
    $expected->addTyphoonType($traversableObject);

    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $property = $reflector->getProperty('primaryType');
    $property->setAccessible(true);

    $actual = $property->getValue($type);

    $this->assertEquals($expected, $actual);
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Typhoon\Type\Traversable::checkPrimary
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}