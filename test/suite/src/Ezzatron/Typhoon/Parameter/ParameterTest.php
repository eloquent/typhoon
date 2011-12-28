<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Parameter;

use Phake;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\MixedType;

class ParameterTest extends \Ezzatron\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_parameter = new Parameter;
    $this->_type = Phake::mock('Ezzatron\Typhoon\Type\Type');
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\Parameter::__construct
   * @covers Ezzatron\Typhoon\Parameter\Parameter::setType
   * @covers Ezzatron\Typhoon\Parameter\Parameter::type
   * @group parameter
   * @group core
   */
  public function testType()
  {
    $this->assertEquals(new MixedType, $this->_parameter->type());
    
    $this->_parameter->setType($this->_type);

    $this->assertSame($this->_type, $this->_parameter->type());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\Parameter::setOptional
   * @covers Ezzatron\Typhoon\Parameter\Parameter::optional
   * @group parameter
   * @group core
   */
  public function testOptional()
  {
    $this->assertFalse($this->_parameter->optional());

    $this->_parameter->setOptional(new Boolean(true));

    $this->assertTrue($this->_parameter->optional());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\Parameter::setName
   * @covers Ezzatron\Typhoon\Parameter\Parameter::name
   * @group parameter
   * @group core
   */
  public function testName()
  {
    $this->assertNull($this->_parameter->name());

    $name = 'foo';
    $this->_parameter->setName(new String($name));

    $this->assertEquals($name, $this->_parameter->name());
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\Parameter::setDescription
   * @covers Ezzatron\Typhoon\Parameter\Parameter::description
   * @group parameter
   * @group core
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