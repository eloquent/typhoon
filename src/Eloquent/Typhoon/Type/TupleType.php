<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;

class TupleType implements NamedType, SubTyped\SubTypedType
{
  public function __construct()
  {
    $this->innerType = new ArrayType;
  }

  /**
   * @param array $subTypes
   */
  public function setTyphoonTypes(array $subTypes)
  {
    $this->types = $subTypes;
  }

  /**
   * @return array
   */
  public function typhoonTypes()
  {
    return $this->types;
  }

  /**
   * @param mixed $value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!$this->innerType->typhoonCheck($value))
    {
      return false;
    }

    $expectedKeys = array();
    if ($this->typhoonTypes())
    {
      $expectedKeys = range(0, count($this->typhoonTypes()) - 1);
    }
    if (array_keys($value) !== $expectedKeys)
    {
      return false;
    }

    $i = 0;
    foreach ($this->typhoonTypes() as $type)
    {
      if (!$type->typhoonCheck($value[$i]))
      {
        return false;
      }

      $i ++;
    }

    return true;
  }

  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_TUPLE()->value();
  }

  /**
   * @var ArrayType
   */
  protected $innerType;

  /**
   * @var array
   */
  protected $types = array();
}
