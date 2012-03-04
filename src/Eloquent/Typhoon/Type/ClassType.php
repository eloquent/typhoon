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

class ClassType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerType = new StringType;
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

    $class = $this->typhoonAttributes()->get(self::ATTRIBUTE_INSTANCE_OF, null);
    $autoload = $this->typhoonAttributes()->get(self::ATTRIBUTE_AUTOLOAD, true);

    if (
      $class
      && $class !== $value
      && !is_a($value, $class, $autoload)
    ) {
      return false;
    }

    return class_exists($value, $autoload);
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature->set(self::ATTRIBUTE_INSTANCE_OF, new StringType);
    $attributeSignature->set(self::ATTRIBUTE_AUTOLOAD, new BooleanType);
  }

  const ATTRIBUTE_INSTANCE_OF = 'instanceOf';
  const ATTRIBUTE_AUTOLOAD = 'autoload';

  /**
   * @var StringType
   */
  protected $innerType;
}
