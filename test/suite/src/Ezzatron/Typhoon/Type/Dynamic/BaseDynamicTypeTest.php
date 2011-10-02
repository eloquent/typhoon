<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Dynamic;

use ReflectionClass;
use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Primitive\String;

class BaseDynamicTypeTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType');
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType::__construct
   * @covers Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType::typhoonAttributes
   */
  public function testConstruct()
  {
    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolder(new String(get_class($this->_type)));

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $this->assertEquals($expected, $this->_type->typhoonAttributes());


    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType', array(new Attributes));

    $expectedSignature = new AttributeSignature;
    $expectedSignature->setHolder(new String(get_class($this->_type)));

    $expected = new Attributes;
    $expected->setSignature($expectedSignature);

    $this->assertEquals($expected, $this->_type->typhoonAttributes());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType::attributeSignature
   * @covers Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType::configureAttributeSignature
   */
  public function testAttributeSignature()
  {
    $reflector = new ReflectionClass(__NAMESPACE__.'\BaseDynamicType');
    $property = $reflector->getProperty('attributeSignatures');
    $property->setAccessible(true);
    $property->setValue(null, array());

    $expected = new AttributeSignature;
    $expected->setHolder(new String(get_class($this->_type)));
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