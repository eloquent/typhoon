<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Parameter\ParameterList\Exception;

use Ezzatron\Typhoon\Primitive\Integer;

class UndefinedParameterExceptionTest extends \Ezzatron\Typhoon\Test\ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\UndefinedParameterException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_index);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_index = new Integer(0);
  }

  /**
   * @covers Ezzatron\Typhoon\Parameter\ParameterList\Exception\UndefinedParameterException::__construct
   * @group exceptions
   * @group parameter
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals('No parameter defined for index '.$this->_index.'.', $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var Integer
   */
  protected $_index;
}