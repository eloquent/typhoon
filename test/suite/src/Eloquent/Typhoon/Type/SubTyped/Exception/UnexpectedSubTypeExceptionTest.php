<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\SubTyped\Exception;

use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;

class UnexpectedSubTypeExceptionTest extends \Eloquent\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UnexpectedSubTypeException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_class, $this->_position);
  }

  protected function setUp()
  {
    parent::setUp();

    $this->_class = new String('foo');
    $this->_position = new Integer(666);
  }

  /**
   * @covers Eloquent\Typhoon\Type\SubTyped\Exception\UnexpectedSubTypeException::__construct
   * @group exceptions
   * @group type
   * @group type-registry
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals("Unexpected subtype at position ".$this->_position." in type class '".$this->_class."'.", $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_class;

  /**
   * @var Integer
   */
  protected $_integer;
}
