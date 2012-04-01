<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\SubTyped;

use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\SubTyped\Exception\UnexpectedSubTypeException;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;

abstract class BaseTraversableType implements TraversableType
{
  public function __construct()
  {
    $this->subType = new MixedType;
    $this->keyType = new MixedType;
  }

  /**
   * @param Type $subType
   */
  public function setTyphoonSubType(Type $subType)
  {
    $this->subType = $subType;
  }

  /**
   * @return Type
   */
  public function typhoonSubType()
  {
    return $this->subType;
  }

  /**
   * @param Type $keyType
   */
  public function setTyphoonKeyType(Type $keyType)
  {
    $this->keyType = $keyType;
  }

  /**
   * @return Type
   */
  public function typhoonKeyType()
  {
    return $this->keyType;
  }

  /**
   * @param array $subTypes
   */
  public function setTyphoonTypes(array $subTypes)
  {
    $numSubTypes = count($subTypes);

    if ($numSubTypes > 2)
    {
      throw new UnexpectedSubTypeException(new String(get_called_class()), new Integer(2));
    }

    if ($numSubTypes > 0)
    {
      $this->setTyphoonSubType(array_pop($subTypes));

      if ($numSubTypes > 1)
      {
        $this->setTyphoonKeyType(array_pop($subTypes));
      }
      else
      {
        $this->setTyphoonKeyType(new MixedType);
      }
    }
  }

  /**
   * @return array
   */
  public function typhoonTypes()
  {
    $keyTypeIsMixed = $this->typhoonKeyType() instanceof MixedType;
    $subTypeIsMixed = $this->typhoonSubType() instanceof MixedType;

    if ($keyTypeIsMixed && $subTypeIsMixed)
    {
      return array();
    }

    if ($keyTypeIsMixed)
    {
      return array($this->typhoonSubType());
    }

    return array(
      $this->typhoonKeyType(),
      $this->typhoonSubType(),
    );
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!$this->checkPrimary($value))
    {
      return false;
    }

    $subType = $this->typhoonSubType();
    $keyType = $this->typhoonKeyType();

    if ($subType instanceof MixedType && $keyType instanceof MixedType)
    {
      return true;
    }

    foreach ($value as $key => $subValue)
    {
      if ($subType && !$subType->typhoonCheck($subValue))
      {
        return false;
      }

      if ($keyType && !$keyType->typhoonCheck($key))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  abstract protected function checkPrimary($value);

  /**
   * @var Type
   */
  protected $subType;

  /**
   * @var Type
   */
  protected $keyType;
}
