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

class ResourceType extends Dynamic\BaseDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!is_resource($value))
    {
      return false;
    }

    if (
      $this->hasAttributes()
      && $type = $this->typhoonAttributes()->get(self::ATTRIBUTE_TYPE, null)
    )
    {
      return get_resource_type($value) == $type;
    }

    return true;
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature[self::ATTRIBUTE_TYPE] = new StringType;
  }

  const ATTRIBUTE_TYPE = 'type';

  const TYPE_STREAM = 'stream';
  const TYPE_UNKNOWN = 'Unknown';
}