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
      && $types = $this->typhoonAttributes()->get(self::ATTRIBUTE_TYPE, array())
    )
    {
      $type = get_resource_type($value);

      if (!is_array($types))
      {
        $types = array($types);
      }
      foreach ($types as $thisType)
      {
        if ($thisType == $type)
        {
          return true;
        }
      }

      return false;
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
    $typeArrayType = new ArrayType;
    $typeArrayType->setTyphoonSubType(new StringType);
    $typeType = new Composite\OrType;
    $typeType->addTyphoonType(new StringType);
    $typeType->addTyphoonType($typeArrayType);
    $attributeSignature->set(self::ATTRIBUTE_TYPE, $typeType);
  }

  const ATTRIBUTE_TYPE = 'type';

  const TYPE_SOCKET = 'Socket';
  const TYPE_STREAM = 'stream';
  const TYPE_UNKNOWN = 'Unknown';
}