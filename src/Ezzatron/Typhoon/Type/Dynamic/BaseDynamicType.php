<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Dynamic;

use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\BaseType;

abstract class BaseDynamicType extends BaseType implements DynamicType
{
  public function __construct(array $attributes = null)
  {
    if (null === $attributes)
    {
      $attributes = array();
    }

    $this->attributes = $attributes;
  }

  /**
   * @return Attributes
   */
  public function typhoonAttributes()
  {
    $attributes = Attributes::adapt($this->attributes);
    $attributes->setSignature(static::attributeSignature($this));
    
    return $attributes;
  }

  /**
   * @return boolean
   */
  protected function hasAttributes()
  {
    return count($this->attributes) > 0;
  }

  /**
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function attributeSignature(BaseDynamicType $type)
  {
    $class = get_called_class();

    if (!array_key_exists($class, self::$attributeSignatures))
    {
      self::$attributeSignatures[$class] = new AttributeSignature;
      self::$attributeSignatures[$class]->setHolderName(new String($class));
      static::configureAttributeSignature(self::$attributeSignatures[$class], $type);
    }

    return self::$attributeSignatures[$class];
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, BaseDynamicType $type) {}

  /**
   * @var array
   */
  static protected $attributeSignatures = array();

  /**
   * @var array
   */
  protected $attributes;
}
