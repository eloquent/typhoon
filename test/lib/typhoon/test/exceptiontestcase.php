<?php

namespace Typhoon\Test;

abstract class ExceptionTestCase extends \PHPUnit_Framework_TestCase
{
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
   * @return \Typhoon\Exception\Exception
   */
  protected function exceptionFixture(array $arguments = null, \Exception $previous = null)
  {
    if (null === $arguments) $arguments = $this->defaultArguments();
    if ($previous) $arguments[] = $previous;

    $reflector = new \ReflectionClass($this->exceptionClass());

    if ($reflector->isAbstract())
    {
      return $this->getMockForAbstractClass($this->exceptionClass(), $arguments);
    }

    return $reflector->newInstanceArgs($arguments);
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