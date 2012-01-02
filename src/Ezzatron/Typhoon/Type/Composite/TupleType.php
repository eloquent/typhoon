<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Composite;

use Ezzatron\Typhoon\Type\ArrayType;

class TupleType extends BaseCompositeType
{
  public function __construct()
  {
    $this->innerType = new ArrayType;
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
    if ($this->types)
    {
      $expectedKeys = range(0, count($this->types) - 1);
    }
    if (array_keys($value) !== $expectedKeys)
    {
      return false;
    }

    $i = 0;
    foreach ($this->types as $type)
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
   * @var ArrayType
   */
  protected $innerType;
}
