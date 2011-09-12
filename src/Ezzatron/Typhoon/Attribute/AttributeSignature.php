<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute;

use Ezzatron\Typhoon\Collection\Collection;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\TypeType;

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
    return new TypeType;
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKey()
  {
    return false;
  }
}