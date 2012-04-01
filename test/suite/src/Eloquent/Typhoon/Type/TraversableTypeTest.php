<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use Eloquent\Typhoon\Attribute\Attributes;
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Composite\AndType;
use Eloquent\Typhoon\Type\Composite\OrType;
use Eloquent\Typhoon\Type\StringType;
use Phake;
use ReflectionClass;
use ReflectionObject;
use stdClass;

class TraversableTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $iterator = Phake::mock('Iterator');
    $iteratorAggregate = Phake::mock('IteratorAggregate');
    $attributesIterator = array(TraversableType::ATTRIBUTE_INSTANCE_OF => get_class($iterator));
    $attributesNonTraversable = array(TraversableType::ATTRIBUTE_INSTANCE_OF => 'stdClass');

    $attributesIteratorAndMockIterator = array(ObjectType::ATTRIBUTE_INSTANCE_OF => array(
      'Iterator',
      get_class($iterator),
    ));

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

      array(true,  $iterator,           $attributesIterator),  // #11: specific class traversable
      array(false, $iteratorAggregate,  $attributesIterator),  // #12: failure for specific class traversable

      array(false, new stdClass,        $attributesNonTraversable),  // #13: failure when class match but not traversable

      array(true,  $iterator,           $attributesIteratorAndMockIterator),  // #14: traversable of two simultaneous types success
      array(false, new \ArrayIterator,  $attributesIteratorAndMockIterator),  // #15: traversable of two simultaneous types partial failure
      array(false, $iteratorAggregate,  $attributesIteratorAndMockIterator),  // #16: traversable of two simultaneous types complete failure
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
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_TRAVERSABLE()->value();
  }

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::__construct
   * @covers Eloquent\Typhoon\Type\TraversableType::typhoonAttributes
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testConstruct()
  {
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $type = $this->typeFixture();

    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolderName(new String(get_class($type)));
    $expectedSignature[TraversableType::ATTRIBUTE_INSTANCE_OF] = $stringOrArrayOfStringType;

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $this->assertEquals($expected, $type->typhoonAttributes());


    $type = $this->typeFixture(array());

    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolderName(new String(get_class($type)));
    $expectedSignature[TraversableType::ATTRIBUTE_INSTANCE_OF] = $stringOrArrayOfStringType;

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $this->assertEquals($expected, $type->typhoonAttributes());
  }

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::attributeSignature
   * @covers Eloquent\Typhoon\Type\TraversableType::configureAttributeSignature
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testAttributeSignature()
  {
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $reflector = new ReflectionClass(__NAMESPACE__.'\TraversableType');
    $property = $reflector->getProperty('attributeSignatures');
    $property->setAccessible(true);
    $property->setValue(null, array());

    $expected = new AttributeSignature;
    $expected->setHolderName(new String($this->typeClass()));
    $expected[TraversableType::ATTRIBUTE_INSTANCE_OF] = $stringOrArrayOfStringType;

    $object = new TraversableType;
    $actual = $object->typhoonAttributes()->signature();

    $this->assertEquals($expected, $actual);

    $object = new TraversableType;

    $this->assertEquals($actual, $object->typhoonAttributes()->signature());
  }

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::typhoonAttributes
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testSetTyphoonAttribute()
  {
    $type = $this->typeFixture(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => 'foo',
    ));

    $this->assertEquals('foo', $type->typhoonAttributes()->get(TraversableType::ATTRIBUTE_INSTANCE_OF));
  }

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::typhoonAttributes
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testSetTyphoonAttributeFailure()
  {
    $type = $this->typeFixture(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => 1,
    ));

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedAttributeException');
    $type->typhoonAttributes();
  }

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::primaryType
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testPrimaryType()
  {
    $traversableObject = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'Traversable',
    ));
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
   * @covers Eloquent\Typhoon\Type\TraversableType::primaryType
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testPrimaryTypeWithInstanceOf()
  {
    $specificObject = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'Foo',
    ));
    $traversableObject = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'Traversable',
    ));
    $expected = new AndType;
    $expected->addTyphoonType($specificObject);
    $expected->addTyphoonType($traversableObject);

    $type = $this->typeFixture(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => 'Foo',
    ));

    $reflector = new ReflectionObject($type);
    $method = $reflector->getMethod('primaryType');
    $method->setAccessible(true);

    $actual = $method->invoke($type);

    $this->assertEquals($expected, $actual);
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::checkPrimary
   * @covers Eloquent\Typhoon\Type\TraversableType::hasAttributes
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   * @group sub-typed-type
   * @group traversable-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\TraversableType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
