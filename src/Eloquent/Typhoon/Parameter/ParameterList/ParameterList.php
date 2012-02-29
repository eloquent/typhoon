<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter\ParameterList;

use Eloquent\Typhoon\Collection\Collection;
use Eloquent\Typhoon\Collection\Exception\UndefinedKeyException;
use Eloquent\Typhoon\Exception\NotImplementedException;
use Eloquent\Typhoon\Primitive\Boolean;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\ParameterType;

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
