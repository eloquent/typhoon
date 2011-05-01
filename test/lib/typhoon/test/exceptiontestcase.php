<?php

namespace Typhoon\Test;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Typhoon\Exception\Exception;

abstract class ExceptionTestCase extends PHPUnit_Framework_TestCase
{
  /**
   * @return Exception
   */
  protected function exceptionFixture(array $arguments = null, \Exception $previous = null)
  {
    if (null === $arguments) $arguments = $this->defaultArguments();
    if ($previous) $arguments[] = $previous;

    $reflector = new ReflectionClass($this->exceptionClass());

    if ($reflector->isAbstract())
    {
      return $this->getMockForAbstractClass($this->exceptionClass(), $arguments);
    }

    return $reflector->newInstanceArgs($arguments);
  }
  
  protected function setUp()
  {
    $this->_previous = new \Exception();
  }

  public function testConstructor()
  {
    $this->assertNull($this->exceptionFixture()->getPrevious());
    $this->assertSame($this->_previous, $this->exceptionFixture(null, $this->_previous)->getPrevious());
  }

  /**
   * @return string
   */
  abstract protected function exceptionClass();

  /**
   * @return array
   */
  abstract protected function defaultArguments();

  /**
   * @var \Exception
   */
  protected $_previous;
}