<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute\Exception;

use Ezzatron\Typhoon\Primitive\String;

class UnsupportedAttributeExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnsupportedAttributeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_attribute, $this->_holder);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_attribute = new String('bar');
    $this->_holder = new String('foo');
  }

  /**
   * @covers Ezzatron\Typhoon\Attribute\Exception\UnsupportedAttributeException::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals(
      "The attribute '".$this->_attribute."' is not supported by '".$this->_holder."'."
      , $this->exceptionFixture()->getMessage()
    );
    $this->assertEquals(
      "The attribute '".$this->_attribute."' is not supported."
      , $this->exceptionFixture(array($this->_attribute))->getMessage()
    );

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_attribute;

  /**
   * @var String
   */
  protected $_holder;
}