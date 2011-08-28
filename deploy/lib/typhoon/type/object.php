<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use Typhoon\AttributeSignature;
use Typhoon\BaseDynamicType;
use Typhoon\Type\String as StringType;

class Object extends BaseDynamicType
{
  /**
   * @return AttributeSignature
   */
  static public function attributeSignature()
  {
    if (!self::$attributeSignature)
    {
      self::$attributeSignature = new AttributeSignature;
      self::$attributeSignature[self::ATTRIBUTE_INSTANCE_OF] = new StringType;
    }

    return self::$attributeSignature;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if ($class = $this->typhoonAttributes()->get(self::ATTRIBUTE_INSTANCE_OF, null))
    {
      return $value instanceof $class;
    }

    return is_object($value);
  }

  const ATTRIBUTE_INSTANCE_OF = 'instanceOf';

  /**
   * @var AttributeSignature
   */
  static protected $attributeSignature;
}