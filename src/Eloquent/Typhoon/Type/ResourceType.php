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
use Eloquent\Typhoon\Attribute\AttributeSignature;

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

      return in_array($type, $types, true);
    }

    return true;
  }

  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_RESOURCE()->_value();
  }

  const ATTRIBUTE_TYPE = 'type';

  const TYPE_SOCKET = 'Socket';
  const TYPE_STREAM = 'stream';
  const TYPE_UNKNOWN = 'Unknown';

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $attributeSignature->set(self::ATTRIBUTE_TYPE, $stringOrArrayOfStringType);
  }
}
