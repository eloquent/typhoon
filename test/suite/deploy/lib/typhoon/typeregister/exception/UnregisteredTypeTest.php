<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\TypeRegistry\Exception;

use Typhoon\Primitive\String;
use Typhoon\Test\ExceptionTestCase;
use Typhoon\Type;

class UnregisteredTypeTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnregisteredType';
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
    $this->_typeName = new String('foo');
  }

  /**
   * @covers \Typhoon\TypeRegistry\Exception\UnregisteredType::__construct
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