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

use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\Boolean;

class EnumerationType extends Dynamic\BaseDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    $class = $this->typhoonAttributes()->get(self::ATTRIBUTE_CLASS);

    return in_array($value, $class::values(), true);
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature->set(self::ATTRIBUTE_CLASS, new ClassType(array(
      ClassType::ATTRIBUTE_INSTANCE_OF => 'Eloquent\Typhoon\Enumeration\Enumeration',
    )), new Boolean(true));
  }

  const ATTRIBUTE_CLASS = 'class';
}
