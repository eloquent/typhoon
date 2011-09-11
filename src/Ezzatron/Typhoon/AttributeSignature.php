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

use Typhoon\Type\String as StringType;
use Typhoon\Type\TyphoonType;

class AttributeSignature extends Collection
{
  /**
   * @return Type
   */
  protected function keyType()
  {
    return new StringType;
  }

  /**
   * @param mixed $key
   *
   * @return Type
   */
  protected function valueType($key)
  {
    return new TyphoonType;
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKey()
  {
    return false;
  }
}