<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Phake;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Test\TestCase;
use Ezzatron\Typhoon\Type\Mixed as MixedType;

class ParameterTest extends TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_parameter = new Parameter;
    $this->_type = Phake::mock(__NAMESPACE__.'\Type');
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter::__construct
   * @covers Ezzatron\Typhoon\Parameter::setType
   * @covers Ezzatron\Typhoon\Parameter::type
   */
  public function testType()
  {
    $this->assertEquals(new MixedType, $this->_parameter->type());
    
    $this->_parameter->setType($this->_type);

    $this->assertSame($this->_type, $this->_parameter->type());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter::setOptional
   * @covers Ezzatron\Typhoon\Parameter::optional
   */
  public function testOptional()
  {
    $this->assertFalse($this->_parameter->optional());

    $this->_parameter->setOptional(new Boolean(true));

    $this->assertTrue($this->_parameter->optional());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter::setName
   * @covers Ezzatron\Typhoon\Parameter::name
   */
  public function testName()
  {
    $this->assertNull($this->_parameter->name());

    $name = 'foo';
    $this->_parameter->setName(new String($name));

    $this->assertEquals($name, $this->_parameter->name());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter::setDescription
   * @covers Ezzatron\Typhoon\Parameter::description
   */
  public function testDescription()
  {
    $this->assertNull($this->_parameter->description());

    $description = 'foo';
    $this->_parameter->setDescription(new String($description));

    $this->assertEquals($description, $this->_parameter->description());
  }

  /**
   * @var Parameter
   */
  protected $_parameter;

  /**
   * @var Type
   */
  protected $_type;
}