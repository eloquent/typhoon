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

use PHPUnit_Framework_TestCase;
use stdClass;
use Typhoon;
use Typhoon\Renderer\Type as TypeRenderer;
use Typhoon\TypeRegistry;

class TypeTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $this->_type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $this->_typeRenderer = $this->getMockForAbstractClass(__NAMESPACE__.'\Renderer\Type');
  }

  /**
   * @covers Typhoon\Type::__construct
   * @covers Typhoon\Type::arguments
   */
  public function testConstructorArguments()
  {
    $argument_0 = 'foo';
    $argument_1 = 'bar';
    $type = $this->getMock('Typhoon\Type', array('construct', 'check'), array(), '', false);
    $type
      ->expects($this->once())
      ->method('construct')
      ->with($this->equalTo($argument_0), $this->equalTo($argument_1))
    ;
    $expected = array($argument_0, $argument_1);

    $type->__construct($argument_0, $argument_1);

    $this->assertEquals($expected, $type->arguments());
  }
  
  /**
   * @covers Typhoon\Type::assert
   */
  public function testAssertPass()
  {
    $value = 'foo';
    
    $this->_type
      ->expects($this->once())
      ->method('check')
      ->with($value)
      ->will($this->returnValue(true))
    ;

    $this->_type->assert($value);
  }
  
  /**
   * @covers Typhoon\Type::assert
   */
  public function testAssertFailure()
  {
    $value = 'foo';
    
    $this->_type
      ->expects($this->once())
      ->method('check')
      ->with($value)
      ->will($this->returnValue(false))
    ;

    $this->setExpectedException(__NAMESPACE__.'\Type\Exception\UnexpectedType');
    $this->_type->assert($value);
  }

  /**
   * @covers Typhoon\Type::__toString
   */
  public function testToString()
  {
    $rendered = 'foo';
    $this->_typeRenderer
      ->expects($this->once())
      ->method('render')
      ->with($this->equalTo($this->_type))
      ->will($this->returnValue($rendered))
    ;
    $this->_type->setRenderer($this->_typeRenderer);

    $this->assertEquals($rendered, (string)$this->_type);
  }

  /**
   * @covers Typhoon\Type::__toString
   */
  public function testToStringUnregistered()
  {
    $this->_typeRenderer->setTypeRegistry(new TypeRegistry);

    $type = $this->getMockForAbstractClass(__NAMESPACE__.'\Type');
    $rendered = get_class($type);

    $this->assertEquals($rendered, (string)$type);
  }

  /**
   * @covers Typhoon\Type::setRenderer
   * @covers Typhoon\Type::renderer
   */
  public function testRenderer()
  {
    $renderer = Typhoon::instance()->typeRenderer();

    $this->assertSame($renderer, $this->_type->renderer());

    $this->_type->setRenderer($this->_typeRenderer);

    $this->assertSame($this->_typeRenderer, $this->_type->renderer());
  }

  /**
   * @var Type
   */
  protected $_type;

  /**
   * @var TypeRenderer
   */
  protected $_typeRenderer;
}