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

class ObjectType extends Dynamic\BaseDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (
      $this->hasAttributes()
      && $classes = $this->typhoonAttributes()->get(self::ATTRIBUTE_INSTANCE_OF, array())
    )
    {
      if (!is_array($classes))
      {
        $classes = array($classes);
      }
      foreach ($classes as $class)
      {
        if (!$value instanceof $class)
        {
          return false;
        }
      }

      return true;
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
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);
    
    $attributeSignature->set(self::ATTRIBUTE_INSTANCE_OF, $stringOrArrayOfStringType);
  }

  const ATTRIBUTE_INSTANCE_OF = 'instanceOf';
}
