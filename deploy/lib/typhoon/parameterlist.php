<?php

namespace Typhoon;

class ParameterList implements \ArrayAccess, \IteratorAggregate
{
  public function add(Parameter $parameter)
  {
    $this->parameters[] = $parameter;
  }

  /**
   * @return boolean
   */
  public function offsetExists($index)
  {
    return isset($this->parameters[$index]);
  }

  public function offsetSet($index, $parameter)
  {
    if (!$parameter instanceof Parameter) throw new Type\Exception\UnexpectedType(
      new Type\Object(__NAMESPACE__.'\Parameter')
    );

    if (null === $index)
    {
      $this->parameters[] = $parameter;
    }
    else
    {
      $this->parameters[$index] = $parameter;
    }
  }

  /**
   * @return Parameter
   */
  public function offsetGet($index)
  {
    if (isset($this[$index])) return $this->parameters[$index];

    throw new ParameterList\Exception\UndefinedParameter($index);
  }

  public function offsetUnset($index)
  {
    throw new \Typhoon\Exception\NotImplemented('Unset');
  }

  /**
   * @return \ArrayIterator
   */
  public function iterator()
  {
    return new \ArrayIterator($this->parameters);
  }

  /**
   * @return \ArrayIterator
   */
  public function getIterator()
  {
    return $this->iterator();
  }

  /**
   * @var array
   */
  protected $parameters = array();
}