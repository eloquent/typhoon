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
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\StringType;

class TraversableType extends Traversable\BaseTraversableType implements Dynamic\DynamicType
{
  /**
   * @return AttributeSignature
   */
  static public function attributeSignature()
  {
    if (!self::$attributeSignature)
    {
      self::$attributeSignature = new AttributeSignature;
      self::$attributeSignature[self::ATTRIBUTE_INSTANCE_OF] = new StringType;
    }

    return self::$attributeSignature;
  }

  /**
   * @return Attributes
   */
  public function typhoonAttributes()
  {
    if (!$this->attributes)
    {
      $this->attributes = new Attributes;
      $this->attributes->setSignature(static::attributeSignature());
    }

    return $this->attributes;
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
   * @var AttributeSignature
   */
  static protected $attributeSignature;

  /**
   * @var Attributes
   */
  protected $attributes;
}