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

class UnregisteredTypeAliasTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnregisteredTypeAlias';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_alias);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_alias = new String('foo');
  }

  /**
   * @covers Typhoon\TypeRegistry\Exception\UnregisteredTypeAlias::__construct
   */
  public function testConstructor()
  {
    $this->assertEquals("No type registered for alias '".$this->_alias."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_alias;
}