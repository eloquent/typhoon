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

use Ezzatron\Typhoon\AndType;
use Ezzatron\Typhoon\Attributes;
use Ezzatron\Typhoon\AttributeSignature;
use Ezzatron\Typhoon\BaseTraversableType;
use Ezzatron\Typhoon\DynamicType;
use Ezzatron\Typhoon\OrType;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\Object;
use Ezzatron\Typhoon\Type\String as StringType;

class Traversable extends BaseTraversableType implements DynamicType
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
    $traversableObject = new Object;
    $traversableObject->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, 'Traversable');

    if ($class = $this->typhoonAttributes()->get(self::ATTRIBUTE_INSTANCE_OF, null))
    {
      $specificClassObject = new Object;
      $specificClassObject->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, $class);

      $primaryType = new AndType;
      $primaryType->addTyphoonType($specificClassObject);
      $primaryType->addTyphoonType($traversableObject);
    }
    else
    {
      $primaryType = new OrType;
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