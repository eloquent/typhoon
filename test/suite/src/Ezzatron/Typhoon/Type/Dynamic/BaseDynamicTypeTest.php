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

use Phake;
use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;

class BaseDynamicTypeTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_type = Phake::partialMock(__NAMESPACE__.'\BaseDynamicType');
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType::attributeSignature
   */
  public function testAttributeSignature()
  {
    $this->assertEquals(new AttributeSignature, BaseDynamicType::attributeSignature());
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Dynamic\BaseDynamicType::typhoonAttributes
   */
  public function testTyphoonAttributes()
  {
    $expected = new Attributes;
    $expected->setSignature(new AttributeSignature);

    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\BaseDynamicType');
    $this->assertEquals($expected, $type->typhoonAttributes());
  }

  /**
   * @var BaseDynamicType
   */
  protected $_type;
}