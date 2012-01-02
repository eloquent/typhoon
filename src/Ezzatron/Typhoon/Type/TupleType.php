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
use Ezzatron\Typhoon\Primitive\Boolean;

class TupleType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerType = new ArrayType;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!$this->innerType->typhoonCheck($value))
    {
      return false;
    }

    $expectedKeys = range(
      0
      , count($this->typhoonAttributes()->get(self::ATTRIBUTE_TYPES)) - 1
    );
    if (array_keys($value) !== $expectedKeys)
    {
      return false;
    }

    $i = 0;
    foreach ($this->typhoonAttributes()->get(self::ATTRIBUTE_TYPES) as $type)
    {
      if (!$type->typhoonCheck($value[$i]))
      {
        return false;
      }

      $i ++;
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
    $arrayOfTypesType = new ArrayType;
    $arrayOfTypesType->setTyphoonKeyType(new IntegerType);
    $arrayOfTypesType->setTyphoonSubType(new TypeType);
    
    $attributeSignature->set(self::ATTRIBUTE_TYPES, $arrayOfTypesType, new Boolean(true));
  }

  const ATTRIBUTE_TYPES = 'types';

  /**
   * @var ArrayType
   */
  protected $innerType;
}
