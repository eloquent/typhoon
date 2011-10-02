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

use Ezzatron\Typhoon\Collection\Collection;
use Ezzatron\Typhoon\Collection\Exception\UndefinedKeyException;
use Ezzatron\Typhoon\Exception\NotImplementedException;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\ParameterType;

class ParameterList extends Collection
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
   * @param integer|string $key
   */
  public function remove($key)
  {
    throw new NotImplementedException(new String('Remove'));
  }

  /**
   * @return Type
   */
  protected function keyType()
  {
    return new IntegerType;
  }

  /**
   * @return Type
   */
  protected function keySetType()
  {
    return new NullType;
  }

  /**
   * @param mixed $key
   *
   * @return Type
   */
  protected function valueType($key)
  {
    return new ParameterType;
  }

  /**
   * @param mixed $key
   */
  protected function assertKeyExists($key)
  {
    try
    {
      parent::assertKeyExists($key);
    }
    catch (UndefinedKeyException $e)
    {
      throw new Exception\UndefinedParameterException(new Integer($key), $e);
    }
  }

  /**
   * @var boolean
   */
  protected $variableLength = false;
}