<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Parameter\ParameterList;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException;
use Ezzatron\Typhoon\Exception\NotImplementedException;
use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\Null;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\ObjectType;

class ParameterList implements ArrayAccess, Countable, IteratorAggregate
{
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
    new Null($index);

    if (!$parameter instanceof Parameter)
    {
      $parameterType = new ObjectType;
      $parameterType->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'Ezzatron\Typhoon\Parameter\Parameter');

      $parameterParameter = new Parameter;
      $parameterParameter->setType($parameterType);

      throw new UnexpectedArgumentException($parameter, new Integer(1), $parameterParameter);
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

    if (isset($this[$index]))
    {
      return $this->parameters[$index];
    }

    throw new Exception\UndefinedParameterException(new Integer($index));
  }

  /**
   * @param integer $index
   */
  public function offsetUnset($index)
  {
    throw new NotImplementedException(new String('Unset'));
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->parameters);
  }

  /**
   * @return integer
   */
  public function count()
  {
    return count($this->parameters);
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