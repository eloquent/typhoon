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

class SetType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerArrayType = new ArrayType;
    $this->innerEnumerationType = new EnumerationType($attributes);
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!$this->innerArrayType->typhoonCheck($value))
    {
      return false;
    }

    foreach ($value as $subValue) {
      if (!$this->innerEnumerationType->typhoonCheck($subValue))
      {
        return false;
      }
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
    $attributeSignature->set(self::ATTRIBUTE_CLASS, new ClassType(array(
      ClassType::ATTRIBUTE_INSTANCE_OF => 'Eloquent\Typhoon\Enumeration\Enumeration',
    )), new Boolean(true));
  }

  const ATTRIBUTE_CLASS = 'class';

  /**
   * @var ArrayType
   */
  protected $innerArrayType;

  /**
   * @var EnumerationType
   */
  protected $innerEnumerationType;
}
