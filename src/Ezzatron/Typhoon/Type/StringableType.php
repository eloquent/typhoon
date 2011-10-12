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

use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;

class StringableType extends Dynamic\BaseDynamicType
{
  /**
   * @param Attributes|array|null $attributes
   */
  public function __construct($attributes = null)
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
    $attributeSignature[self::ATTRIBUTE_ENCODING] = new StringType;
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