<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Typhoon\Exception\NotImplemented;
use Typhoon\ParameterList\Exception\UndefinedParameter;
use Typhoon\ParameterList\Exception\UnexpectedArgument;
use Typhoon\Primitive\Integer as IntegerPrimitive;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type\Integer as IntegerType;
use Typhoon\Type\Object as ObjectType;

class ParameterList implements ArrayAccess, IteratorAggregate
{
  /**
   * @param Parameter $parameter
   */
  public function add(Parameter $parameter)
  {
    $this->parameters[] = $parameter;
  }

  /**
   * @return boolean
   */
  public function offsetExists($index)
  {
    $this->assertIndex($index);

    return isset($this->parameters[$index]);
  }

  /**
   * @param integer $index
   * @param Parameter $parameter
   */
  public function offsetSet($index, $parameter)
  {
    if (null !== $index) $this->assertIndex($index);

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
   * @param integer $index
   *
   * @return Parameter
   */
  public function offsetGet($index)
  {
    $this->assertIndex($index);

    if (isset($this[$index])) return $this->parameters[$index];

    throw new UndefinedParameter(new IntegerPrimitive($index));
  }

  /**
   * @param integer $index
   */
  public function offsetUnset($index)
  {
    throw new NotImplemented(new StringPrimitive('Unset'));
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->parameters);
  }

  /**
   * @param mixed $index
   */
  protected function assertIndex($index)
  {
    if (!is_integer($index))
    {
      $parameter = new Parameter;
      $parameter->setType(new IntegerType);

      throw new UnexpectedArgument($index, new IntegerPrimitive(0), $parameter);
    }
  }

  /**
   * @var array
   */
  protected $parameters = array();
}