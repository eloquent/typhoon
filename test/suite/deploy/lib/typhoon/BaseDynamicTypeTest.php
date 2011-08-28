<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Phake;
use Typhoon\Test\TestCase;

class BaseDynamicTypeTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_type = Phake::partialMock(__NAMESPACE__.'\BaseDynamicType');
  }

  /**
   * @covers Typhoon\BaseDynamicType::attributeSignature
   */
  public function testAttributeSignature()
  {
    $this->assertEquals(new AttributeSignature, BaseDynamicType::attributeSignature());
  }

  /**
   * @covers Typhoon\BaseDynamicType::typhoonAttributes
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