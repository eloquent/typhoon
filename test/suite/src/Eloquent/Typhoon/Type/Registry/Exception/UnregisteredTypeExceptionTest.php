<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Registry\Exception;

use Eloquent\Typhoon\Primitive\String;

class UnregisteredTypeExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnregisteredTypeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_typeName);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_typeName = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Type\Registry\Exception\UnregisteredTypeException::__construct
   * @covers Eloquent\Typhoon\Exception\UndefinedKeyException
   * @group exceptions
   * @group type
   * @group type-registry
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Type '".$this->_typeName."' not registered.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_typeName;
}
