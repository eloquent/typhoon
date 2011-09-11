<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attributes\Exception;

use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Test\ExceptionTestCase;

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
    return array($this->_className, $this->_attribute);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_className = new String('foo');
    $this->_attribute = new String('bar');
  }

  /**
   * @covers Ezzatron\Typhoon\Attributes\Exception\UnsupportedAttribute::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("The attribute '".$this->_attribute."' is not supported by class '".$this->_className."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_className;

  /**
   * @var String
   */
  protected $_attribute;
}