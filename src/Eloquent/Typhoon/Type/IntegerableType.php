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

class IntegerableType implements NamedType
{
  public function __construct()
  {
    $this->innerNumericType = new NumericType;
    $this->innerIntegerType = new IntegerType;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!$this->innerNumericType->typhoonCheck($value))
    {
      return false;
    }

    return $this->innerIntegerType->typhoonCheck($value + 0);
  }

  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_INTEGERABLE()->_value();
  }

  /**
   * @var NumericType
   */
  protected $innerNumericType;

  /**
   * @var IntegerType
   */
  protected $innerIntegerType;
}
