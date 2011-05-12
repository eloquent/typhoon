<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type\Exception;

use Typhoon\Primitive\String;
use Typhoon\Test\ExceptionTestCase;

class UnsupportedAttributeTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnsupportedAttribute';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_typeName, $this->_attribute);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_typeName = new String('foo');
    $this->_attribute = new String('bar');
  }

  /**
   * @covers Typhoon\Type\Exception\UnsupportedAttribute::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("The attribute '".$this->_attribute."' is not supported by type '".$this->_typeName."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_typeName;

  /**
   * @var String
   */
  protected $_attribute;
}