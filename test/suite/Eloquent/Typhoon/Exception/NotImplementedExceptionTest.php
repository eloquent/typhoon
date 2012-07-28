<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Exception;

use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Test\ExceptionTestCase;

class NotImplementedExceptionTest extends ExceptionTestCase
{
  /**
   * @return string
   */
  protected function exceptionClass()
  {
    return __NAMESPACE__.'\NotImplementedException';
  }

  /**
   * @return array
   */
  protected function defaultArguments()
  {
    return array($this->_feature);
  }

  protected function setUp()
  {
    parent::setUp();
    
    $this->_feature = new String('foo');
  }

  /**
   * @covers Eloquent\Typhoon\Exception\NotImplementedException::__construct
   * @covers Eloquent\Typhoon\Exception\Exception
   * @group exceptions
   * @group core
   */
  public function testConstructor()
  {
    $this->assertEquals($this->_feature.' is not implemented.', $this->exceptionFixture()->getMessage());

    parent::testConstructor();
  }

  /**
   * @var String
   */
  protected $_feature;
}
