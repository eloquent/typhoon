<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

abstract class BaseTraversableType implements TraversableType
{
  /**
   * @param Type $subType
   */
  public function setTyphoonSubType(Type $subType = null)
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
  public function setTyphoonKeyType(Type $keyType = null)
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

    if (!$subType && !$keyType)
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