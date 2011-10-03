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

class StringType extends Dynamic\BaseDynamicType
{
  public function __construct(Attributes $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerType = new SimpleStringType;
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

    if ($encoding = $this->typhoonAttributes()->get(self::ATTRIBUTE_ENCODING, null))
    {
      return mb_check_encoding($value, $encoding);
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
    $attributeSignature[self::ATTRIBUTE_ENCODING] = new StringType;
  }

  const ATTRIBUTE_ENCODING = 'encoding';

  /**
   * @var SimpleStringType
   */
  protected $innerType;
}