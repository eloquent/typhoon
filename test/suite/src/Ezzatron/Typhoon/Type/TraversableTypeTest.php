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

use Phake;
use ReflectionClass;
use ReflectionObject;
use stdClass;
use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Type\Composite\AndType;
use Ezzatron\Typhoon\Type\Composite\OrType;
use Ezzatron\Typhoon\Type\StringType;

class TraversableTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $iterator = Phake::mock('Iterator');
    $iteratorAggregate = Phake::mock('IteratorAggregate');
    $attributesIterator = new Attributes(array(TraversableType::ATTRIBUTE_INSTANCE_OF => get_class($iterator)));
    $attributesNonTraversable = new Attributes(array(TraversableType::ATTRIBUTE_INSTANCE_OF => 'stdClass'));

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
    return __NAMESPACE__.'\TraversableType';
  }

  /**
   * @covers Ezzatron\Typhoon\Type\TraversableType::__construct
   * @covers Ezzatron\Typhoon\Type\TraversableType::typhoonAttributes
   */
  public function testConstruct()
  {
    $type = $this->typeFixture();

    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolder(get_class($type));
    $expectedSignature[TraversableType::ATTRIBUTE_INSTANCE_OF] = new StringType;

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $this->assertEquals($expected, $type->typhoonAttributes());


    $type = $this->typeFixture(new Attributes);

    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolder(get_class($type));
    $expectedSignature[TraversableType::ATTRIBUTE_INSTANCE_OF] = new StringType;

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $this->assertEquals($expected, $type->typhoonAttributes());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\TraversableType::attributeSignature
   * @covers Ezzatron\Typhoon\Type\TraversableType::configureAttributeSignature
   */
  public function testAttributeSignature()
  {
    $reflector = new ReflectionClass(__NAMESPACE__.'\TraversableType');
    $property = $reflector->getProperty('attributeSignatures');
    $property->setAccessible(true);
    $property->setValue(null, array());

    $expected = new AttributeSignature;
    $expected->setHolder($this->typeClass());
    $expected[TraversableType::ATTRIBUTE_INSTANCE_OF] = new StringType;

    $object = new TraversableType;
    $actual = $object->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $object = new TraversableType;

    $this->assertEquals($actual, $object->typhoonAttributes()->signature());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\TraversableType::typhoonAttributes
   */
  public function testSetTyphoonAttribute()
  {
    $type = $this->typeFixture();
    $type->typhoonAttributes()->set(TraversableType::ATTRIBUTE_INSTANCE_OF, 'foo');

    $this->assertEquals('foo', $type->typhoonAttributes()->get(TraversableType::ATTRIBUTE_INSTANCE_OF));
  }

  /**
   * @covers Ezzatron\Typhoon\Type\TraversableType::typhoonAttributes
   */
  public function testSetTyphoonAttributeFailure()
  {
    $type = $this->typeFixture();
    $this->setExpectedException('Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException');
    $type->typhoonAttributes()->set(TraversableType::ATTRIBUTE_INSTANCE_OF, 1);
  }

  /**
   * @covers Ezzatron\Typhoon\Type\TraversableType::primaryType
   */
  public function testPrimaryType()
  {
    $traversableObject = new ObjectType;
    $traversableObject->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'Traversable');
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
   * @covers Ezzatron\Typhoon\Type\TraversableType::primaryType
   */
  public function testPrimaryTypeWithInstanceOf()
  {
    $specificObject = new ObjectType;
    $specificObject->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'Foo');
    $traversableObject = new ObjectType;
    $traversableObject->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'Traversable');
    $expected = new AndType;
    $expected->addTyphoonType($specificObject);
    $expected->addTyphoonType($traversableObject);

    $type = $this->typeFixture();
    $type->typhoonAttributes()->set(TraversableType::ATTRIBUTE_INSTANCE_OF, 'Foo');

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('primaryType');
    $method->setAccessible(true);

    $actual = $method->invoke($type);

    $this->assertEquals($expected, $actual);
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\TraversableType::checkPrimary
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}