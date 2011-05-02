<?php

namespace Typhoon;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Typhoon\Exception\NotImplemented;
use Typhoon\ParameterList\Exception\UndefinedParameter;
use Typhoon\ParameterList\Exception\UnexpectedArgument;
use Typhoon\Primitive\Integer as IntegerPrimitive;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type\Object as ObjectType;

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
      $parameterParameter->setType(new ObjectType(new StringPrimitive(__NAMESPACE__.'\Parameter')));

      throw new UnexpectedArgument($parameter, new IntegerPrimitive(1), $parameterParameter);
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

    throw new UndefinedParameter(new IntegerPrimitive($index));
  }

  public function offsetUnset($index)
  {
    throw new NotImplemented(new StringPrimitive('Unset'));
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