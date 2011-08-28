<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use Typhoon\Attributes;
use Typhoon\AttributeSignature;
use Typhoon\BaseTraversableType;
use Typhoon\DynamicType;
use Typhoon\OrType;
use Typhoon\Type\ArrayType;
use Typhoon\Type\Object;
use Typhoon\Type\String as StringType;

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
      self::$attributeSignature[self::ATTRIBUTE_CLASS] = new StringType;
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

    if ($class = $this->typhoonAttributes()->get(self::ATTRIBUTE_CLASS, null))
    {
      $traversableObject->typhoonAttributes()->set(Object::ATTRIBUTE_CLASS, $class);

      return $traversableObject;
    }

    $traversableObject->typhoonAttributes()->set(Object::ATTRIBUTE_CLASS, 'Traversable');

    $primaryType = new OrType;
    $primaryType->addTyphoonType(new ArrayType);
    $primaryType->addTyphoonType($traversableObject);

    return $primaryType;
  }
  
  const ATTRIBUTE_CLASS = 'class';

  /**
   * @var AttributeSignature
   */
  static protected $attributeSignature;

  /**
   * @var Attributes
   */
  protected $attributes;
}