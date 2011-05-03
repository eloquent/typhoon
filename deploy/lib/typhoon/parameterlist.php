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
use Typhoon\ParameterList\Exception\MissingArgument;
use Typhoon\ParameterList\Exception\UndefinedParameter;
use Typhoon\ParameterList\Exception\UnexpectedArgument;
use Typhoon\Primitive\Boolean;
use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;
use Typhoon\Type\Exception\UnexpectedType;
use Typhoon\Type\Integer as IntegerType;
use Typhoon\Type\Object as ObjectType;

class ParameterList implements ArrayAccess, IteratorAggregate
{
  /**
   * @param array
   */
  public function assert(array $arguments)
  {
    $index = -1;

    foreach ($arguments as $index => $value)
    {
      $indexPrimitive = new Integer($index);
      $parameter = null;

      if (isset($this->parameters[$index]))
      {
        $parameter = $this->parameters[$index];
      }
      elseif ($this->variableLength)
      {
        $parameter = $this->parameters[count($this->parameters) - 1];
      }

      if ($parameter)
      {
        try
        {
          $parameter->type()->assert($value);
        }
        catch (UnexpectedType $e)
        {
          throw new UnexpectedArgument($value, $indexPrimitive, $parameter, $e);
        }

        continue;
      }

      throw new UnexpectedArgument($value, $indexPrimitive);
    }

    $index ++;

    if (count($this->parameters) <= $index) return;

    $parameter = $this->parameters[$index];

    if ($parameter->optional()) return;

    throw new MissingArgument(new Integer($index), $parameter);
  }

  /**
   * @param Boolean $variableLength
   */
  public function setVariableLength(Boolean $variableLength)
  {
    $this->variableLength = $variableLength->value();
  }

  /**
   * @return boolean
   */
  public function variableLength()
  {
    return $this->variableLength;
  }

  /**
   * @return boolean
   */
  public function offsetExists($index)
  {
    new Integer($index);

    return isset($this->parameters[$index]);
  }

  /**
   * @param integer $index
   * @param Parameter $parameter
   */
  public function offsetSet($index, $parameter)
  {
    if (null !== $index) throw new NotImplemented(new String('Setting to a specific index'));

    if (!$parameter instanceof Parameter)
    {
      $parameterParameter = new Parameter;
      $parameterParameter->setType(new ObjectType(new String(__NAMESPACE__.'\Parameter')));

      throw new UnexpectedArgument($parameter, new Integer(1), $parameterParameter);
    }

    $this->parameters[] = $parameter;
  }

  /**
   * @param integer $index
   *
   * @return Parameter
   */
  public function offsetGet($index)
  {
    new Integer($index);

    if (isset($this[$index])) return $this->parameters[$index];

    throw new UndefinedParameter(new Integer($index));
  }

  /**
   * @param integer $index
   */
  public function offsetUnset($index)
  {
    throw new NotImplemented(new String('Unset'));
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->parameters);
  }

  /**
   * @var array
   */
  protected $parameters = array();

  /**
   * @var boolean
   */
  protected $variableLength = false;
}