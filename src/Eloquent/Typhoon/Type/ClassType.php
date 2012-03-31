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
use Eloquent\Typhoon\Primitive\Boolean;
use ReflectionClass;

class ClassType extends BaseClassType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct(new Boolean(false), $attributes);
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature->set(self::ATTRIBUTE_EXTENDS, new StringType);

    parent::configureAttributeSignature($attributeSignature, $type);
  }

  /**
   * @param Attributes $attributes
   * @param ReflectionClass $class
   *
   * @return boolean
   */
  protected function checkInheritance(Attributes $attributes, ReflectionClass $class)
  {
    if ($attributes->keyExists(self::ATTRIBUTE_EXTENDS))
    {
      return $class->isSubclassOf(
        $attributes->get(self::ATTRIBUTE_EXTENDS)
      );
    }

    return parent::checkInheritance($attributes, $class);
  }

  const ATTRIBUTE_EXTENDS = 'extends';
}
