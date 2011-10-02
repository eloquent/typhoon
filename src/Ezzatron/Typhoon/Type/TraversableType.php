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
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\StringType;

class TraversableType extends Traversable\BaseTraversableType implements Dynamic\DynamicType
{
  public function __construct(Attributes $attributes = null)
  {
    if (null === $attributes)
    {
      $attributes = new Attributes;
    }
    else
    {
      $attributes = clone $attributes;
    }
    
    $attributes->setSignature(static::attributeSignature($this));
    $this->attributes = $attributes;
  }

  /**
   * @return Attributes
   */
  public function typhoonAttributes()
  {
    return $this->attributes;
  }

  /**
   * @param TraversableType $type
   *
   * @return AttributeSignature
   */
  static protected function attributeSignature(TraversableType $type)
  {
    $class = get_called_class();

    if (!array_key_exists($class, self::$attributeSignatures))
    {
      $attributeSignature = new AttributeSignature;
      $attributeSignature->setHolder(new String($class));
      static::configureAttributeSignature($attributeSignature, $type);

      self::$attributeSignatures[$class] = $attributeSignature;
    }

    return self::$attributeSignatures[$class];
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param TraversableType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, TraversableType $type)
  {
    $attributeSignature[self::ATTRIBUTE_INSTANCE_OF] = new StringType;
  }
  
  /**
   * @param mixed value
   *
   * @return boolean
   */
  protected function checkPrimary($value)
  {
    return $this->primaryType()->typhoonCheck($value);
  }

  /**
   * @return Type
   */
  protected function primaryType()
  {
    $traversableObject = new ObjectType;
    $traversableObject->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'Traversable');

    if ($class = $this->typhoonAttributes()->get(self::ATTRIBUTE_INSTANCE_OF, null))
    {
      $specificClassObject = new ObjectType;
      $specificClassObject->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, $class);

      $primaryType = new Composite\AndType;
      $primaryType->addTyphoonType($specificClassObject);
      $primaryType->addTyphoonType($traversableObject);
    }
    else
    {
      $primaryType = new Composite\OrType;
      $primaryType->addTyphoonType(new ArrayType);
      $primaryType->addTyphoonType($traversableObject);
    }

    return $primaryType;
  }
  
  const ATTRIBUTE_INSTANCE_OF = 'instanceOf';

  /**
   * @var array
   */
  static protected $attributeSignatures = array();

  /**
   * @var Attributes
   */
  protected $attributes;
}