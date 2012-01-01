<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Registry\Exception;

use Ezzatron\Typhoon\Primitive\String;

class UnregisteredTypeExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
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
   * @covers Ezzatron\Typhoon\Type\Registry\Exception\UnregisteredTypeException::__construct
   * @group exceptions
   * @group type
   * @group type-registry
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("No registered alias for type '".$this->_typeName."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_typeName;
}
