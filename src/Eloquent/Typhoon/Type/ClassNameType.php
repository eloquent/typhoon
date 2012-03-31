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

class ClassNameType extends BaseClassNameType
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
    $attributeSignature->set(self::ATTRIBUTE_INSTANTIABLE, new BooleanType);
    $attributeSignature->set(self::ATTRIBUTE_CLASS_OF, new ClassNameType);

    parent::configureAttributeSignature($attributeSignature, $type);
  }

  /**
   * @param Attributes $attributes
   * @param ReflectionClass $class
   *
   * @return boolean
   */
  protected function checkClass(Attributes $attributes, ReflectionClass $class)
  {
    if ($attributes->keyExists(self::ATTRIBUTE_CLASS_OF))
    {
      $classOf = new ReflectionClass($attributes->get(self::ATTRIBUTE_CLASS_OF));

      if ($classOf->getName() === $class->getName())
      {
        return true;
      }

      return $class->isSubclassOf($classOf);
    }

    if ($attributes->keyExists(self::ATTRIBUTE_INSTANTIABLE))
    {
      if ($class->isInstantiable() !== $attributes->get(self::ATTRIBUTE_INSTANTIABLE))
      {
        return false;
      }
    }

    return parent::checkClass($attributes, $class);
  }

  const ATTRIBUTE_CLASS_OF = 'classOf';
  const ATTRIBUTE_INSTANTIABLE = 'instantiable';
}
