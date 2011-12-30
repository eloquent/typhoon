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

class StringType extends Dynamic\BaseDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!is_string($value))
    {
      return false;
    }

    if (
      $this->hasAttributes()
      && $encodings = $this->typhoonAttributes()->get(self::ATTRIBUTE_ENCODING, array())
    )
    {
      if (!is_array($encodings))
      {
        $encodings = array($encodings);
      }
      foreach ($encodings as $encoding)
      {
        if (mb_check_encoding($value, $encoding))
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
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);
    
    $attributeSignature->set(self::ATTRIBUTE_ENCODING, $stringOrArrayOfStringType);
  }

  const ATTRIBUTE_ENCODING = 'encoding';
}