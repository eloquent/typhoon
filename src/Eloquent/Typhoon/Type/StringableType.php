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

use Eloquent\Typhoon\Attribute\Attributes;
use Eloquent\Typhoon\Attribute\AttributeSignature;

class StringableType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerStringType = new StringType($attributes);
    $this->innerScalarType = new ScalarType;
    $this->innerObjectType = new ObjectType;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if ($this->innerStringType->typhoonCheck($value))
    {
      return true;
    }
    
    if ($this->innerObjectType->typhoonCheck($value))
    {
      if (!method_exists($value, '__toString'))
      {
        return false;
      }
    }
    else if (!$this->innerScalarType->typhoonCheck($value))
    {
      return false;
    }
    
    if ($this->typhoonAttributes()->isEmpty())
    {
      return true;
    }
    
    return $this->innerStringType->typhoonCheck((string)$value);
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

  /**
   * @var ScalarType
   */
  protected $innerScalarType;

  /**
   * @var StringType
   */
  protected $innerStringType;

  /**
   * @var ObjectType
   */
  protected $innerObjectType;
}
