<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Composite;

use Phake;

class BaseCompositeTypeTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    $this->_compositeType = Phake::partialMock(__NAMESPACE__.'\BaseCompositeType');
    $this->_typeA = Phake::mock('Eloquent\Typhoon\Type\NamedType');
    $this->_typeB = Phake::mock('Eloquent\Typhoon\Type\NamedType');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::addTyphoonType
   * @covers Eloquent\Typhoon\Type\Composite\BaseCompositeType::typhoonTypes
   * @group type
   * @group composite-type
   * @group core
   */
  public function testTyphoonTypes()
  {
    $this->assertSame(array(), $this->_compositeType->typhoonTypes());

    $this->_compositeType->addTyphoonType($this->_typeA);
    $this->_compositeType->addTyphoonType($this->_typeB);

    $this->assertSame(array(
      $this->_typeA,
      $this->_typeB,
    ), $this->_compositeType->typhoonTypes());
  }

  /**
   * @var BaseCompositeType
   */
  protected $_compositeType;

  /**
   * @var Type
   */
  protected $_typeA;

  /**
   * @var Type
   */
  protected $_typeB;
}
