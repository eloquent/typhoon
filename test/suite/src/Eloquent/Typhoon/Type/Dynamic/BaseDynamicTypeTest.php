<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Dynamic;

use ReflectionClass;
use ReflectionObject;
use Eloquent\Typhoon\Attribute\Attributes;
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;

class BaseDynamicTypeTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Dynamic\BaseDynamicType::__construct
   * @covers Eloquent\Typhoon\Type\Dynamic\BaseDynamicType::typhoonAttributes
   * @covers Eloquent\Typhoon\Type\Dynamic\BaseDynamicType::hasAttributes
   * @group type
   * @group dynamic-type
   * @group core
   */
  public function testConstruct()
  {
    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolderName(new String(get_class($this->_type)));

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $reflector = new ReflectionObject($this->_type);
    $hasAttributesMethod = $reflector->getMethod('hasAttributes');
    $hasAttributesMethod->setAccessible(true);

    $this->assertEquals($expected, $this->_type->typhoonAttributes());
    $this->assertFalse($hasAttributesMethod->invoke($this->_type));


    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType', array(array()));

    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolderName(new String(get_class($this->_type)));

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $reflector = new ReflectionObject($this->_type);
    $hasAttributesMethod = $reflector->getMethod('hasAttributes');
    $hasAttributesMethod->setAccessible(true);

    $this->assertEquals($expected, $this->_type->typhoonAttributes());
    $this->assertFalse($hasAttributesMethod->invoke($this->_type));


    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType', array(array('foo' => 'bar')));

    $reflector = new ReflectionObject($this->_type);
    $hasAttributesMethod = $reflector->getMethod('hasAttributes');
    $hasAttributesMethod->setAccessible(true);

    $this->assertTrue($hasAttributesMethod->invoke($this->_type));
  }

  /**
   * @covers Eloquent\Typhoon\Type\Dynamic\BaseDynamicType::attributeSignature
   * @covers Eloquent\Typhoon\Type\Dynamic\BaseDynamicType::configureAttributeSignature
   * @group type
   * @group dynamic-type
   * @group core
   */
  public function testAttributeSignature()
  {
    $reflector = new ReflectionClass(__NAMESPACE__.'\BaseDynamicType');
    $property = $reflector->getProperty('attributeSignatures');
    $property->setAccessible(true);
    $property->setValue(null, array());

    $expected = new AttributeSignature;
    $expected->setHolderName(new String(get_class($this->_type)));
    $actual = $this->_type->typhoonAttributes()->signature();
    
    $this->assertEquals($expected, $actual);

    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType');

    $this->assertEquals($actual, $this->_type->typhoonAttributes()->signature());
  }

  /**
   * @var BaseDynamicType
   */
  protected $_type;
}
