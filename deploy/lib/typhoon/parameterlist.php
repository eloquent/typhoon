<?php

namespace Typhoon;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Typhoon\Exception\NotImplemented;
use Typhoon\ParameterList\Exception\UndefinedParameter;
use Typhoon\ParameterList\Exception\UnexpectedArgument;
use Typhoon\Scalar\Integer;
use Typhoon\Scalar\String;
use Typhoon\Type\Object;

class ParameterList implements ArrayAccess, IteratorAggregate
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
    if (!$parameter instanceof Parameter)
    {
      $parameterParameter = new Parameter;
      $parameterParameter->setType(new Object(__NAMESPACE__.'\Parameter'));

      throw new UnexpectedArgument($parameter, new Integer(1), $parameterParameter);
    }

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

    throw new UndefinedParameter(new Integer($index));
  }

  public function offsetUnset($index)
  {
    throw new NotImplemented(new String('Unset'));
  }

  /**
   * @return ArrayIterator
   */
  public function iterator()
  {
    return new ArrayIterator($this->parameters);
  }

  /**
   * @return ArrayIterator
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