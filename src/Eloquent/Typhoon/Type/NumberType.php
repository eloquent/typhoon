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

class NumberType implements NamedType
{
  public function __construct()
  {
    $this->innerType = new Composite\OrType;
    $this->innerType->addTyphoonType(new IntegerType);
    $this->innerType->addTyphoonType(new FloatType);
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    return $this->innerType->typhoonCheck($value);
  }

  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_NUMBER()->_value();
  }

  /**
   * @var Composite\OrType
   */
  protected $innerType;

}
