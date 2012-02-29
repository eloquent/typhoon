<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter;

use Phake;
use Eloquent\Typhoon\Primitive\Boolean;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\MixedType;

class ParameterTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_parameter = new Parameter;
    $this->_type = Phake::mock('Eloquent\Typhoon\Type\Type');
  }

  /**
   * @covers Eloquent\Typhoon\Parameter\Parameter::__construct
   * @covers Eloquent\Typhoon\Parameter\Parameter::setType
   * @covers Eloquent\Typhoon\Parameter\Parameter::type
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
   * @covers Eloquent\Typhoon\Parameter\Parameter::setOptional
   * @covers Eloquent\Typhoon\Parameter\Parameter::optional
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
   * @covers Eloquent\Typhoon\Parameter\Parameter::setName
   * @covers Eloquent\Typhoon\Parameter\Parameter::name
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
   * @covers Eloquent\Typhoon\Parameter\Parameter::setDescription
   * @covers Eloquent\Typhoon\Parameter\Parameter::description
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
