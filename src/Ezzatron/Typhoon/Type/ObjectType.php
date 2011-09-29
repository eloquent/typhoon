<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Attribute\AttributeSignature;

class ObjectType extends Dynamic\BaseDynamicType
{
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

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature[self::ATTRIBUTE_INSTANCE_OF] = new StringType;
  }

  const ATTRIBUTE_INSTANCE_OF = 'instanceOf';
}