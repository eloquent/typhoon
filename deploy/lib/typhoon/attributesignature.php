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

use Typhoon\Type\Object as ObjectType;
use Typhoon\Type\String as StringType;

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
    $type = new ObjectType;
    $type->setTyphoonAttribute(ObjectType::ATTRIBUTE_CLASS, 'Typhoon\Type');

    return $type;
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKey()
  {
    return false;
  }
}