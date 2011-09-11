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
use ReflectionClass;
use ReflectionObject;
use stdClass;
use Typhoon\AndType;
use Typhoon\AttributeSignature;
use Typhoon\OrType;
use Typhoon\Test\TypeTestCase;
use Typhoon\Type\String as StringType;

class TraversableTest extends TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $iterator = Phake::mock('Iterator');
    $iteratorAggregate = Phake::mock('IteratorAggregate');
    $attributesIterator = array(Traversable::ATTRIBUTE_INSTANCE_OF => get_class($iterator));
    $attributesNonTraversable = array(Traversable::ATTRIBUTE_INSTANCE_OF => 'stdClass');

    return array(
      array(false, null),                             // #0: null
      array(false, true),                             // #1: boolean
      array(false, 'string'),                         // #2: string
      array(false, 1),                                // #3: integer
      array(false, .1),                               // #4: float
      array(true,  array()),                          // #5: array
      array(false, new stdClass),                     // #6: object
      array(false, function(){}),                     // #7: closure
      array(false, $this->resourceFixture()),         // #8: resource

      array(true,  $iterator),                         // #9: iterator
      array(true,  $iteratorAggregate),                // #10: iterator aggregate

      array(true,  $iterator, $attributesIterator),            // #11: specific class traversable
      array(false, $iteratorAggregate, $attributesIterator),   // #12: failure for specific class traversable

      array(false, new stdClass, $attributesNonTraversable),   // #13: failure when class match but not traversable
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
   * @covers Typhoon\Type\Traversable::attributeSignature
   */
  public function testAttributeSignature()
  {
    $reflector = new ReflectionClass($this->typeClass());
    $property = $reflector->getProperty('attributeSignature');
    $property->setAccessible(true);
    $property->setValue(null, null);

    $expected = new AttributeSignature;
    $expected[Traversable::ATTRIBUTE_INSTANCE_OF] = new StringType;

    $actual = Traversable::attributeSignature();

    $this->assertEquals($expected, $actual);
    $this->assertSame($actual, Traversable::attributeSignature());
  }

  /**
   * @covers Typhoon\Type\Traversable::typhoonAttributes
   */
  public function testSetTyphoonAttribute()
  {
    $type = $this->typeFixture();
    $type->typhoonAttributes()->set(Traversable::ATTRIBUTE_INSTANCE_OF, 'foo');

    $this->assertEquals('foo', $type->typhoonAttributes()->get(Traversable::ATTRIBUTE_INSTANCE_OF));
  }

  /**
   * @covers Typhoon\Type\Traversable::typhoonAttributes
   */
  public function testSetTyphoonAttributeFailure()
  {
    $type = $this->typeFixture();
    $this->setExpectedException('Typhoon\Assertion\Exception\UnexpectedType');
    $type->typhoonAttributes()->set(Traversable::ATTRIBUTE_INSTANCE_OF, 1);
  }

  /**
   * @covers Typhoon\Type\Traversable::primaryType
   */
  public function testPrimaryType()
  {
    $traversableObject = new Object;
    $traversableObject->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, 'Traversable');
    $expected = new OrType;
    $expected->addTyphoonType(new ArrayType);
    $expected->addTyphoonType($traversableObject);

    $type = $this->typeFixture();

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('primaryType');
    $method->setAccessible(true);

    $actual = $method->invoke($type);

    $this->assertEquals($expected, $actual);
  }

  /**
   * @covers Typhoon\Type\Traversable::primaryType
   */
  public function testPrimaryTypeWithInstanceOf()
  {
    $specificObject = new Object;
    $specificObject->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, 'Foo');
    $traversableObject = new Object;
    $traversableObject->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, 'Traversable');
    $expected = new AndType;
    $expected->addTyphoonType($specificObject);
    $expected->addTyphoonType($traversableObject);

    $type = $this->typeFixture();
    $type->typhoonAttributes()->set(Traversable::ATTRIBUTE_INSTANCE_OF, 'Foo');

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('primaryType');
    $method->setAccessible(true);

    $actual = $method->invoke($type);

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