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

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use Eloquent\Typhoon\Attribute\Attributes;
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;

class TraversableType extends SubTyped\BaseTraversableType implements Dynamic\DynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct();

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
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_TRAVERSABLE()->value();
  }

  const ATTRIBUTE_INSTANCE_OF = 'instanceOf';

  /**
   * @return boolean
   */
  protected function hasAttributes()
  {
    return count($this->attributes) > 0;
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
      self::$attributeSignatures[$class] = new AttributeSignature;
      self::$attributeSignatures[$class]->setHolderName(new String($class));
      static::configureAttributeSignature(self::$attributeSignatures[$class], $type);
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
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $attributeSignature->set(self::ATTRIBUTE_INSTANCE_OF, $stringOrArrayOfStringType);
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
    $traversableObject = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => 'Traversable',
    ));

    if (
      $this->hasAttributes()
      && $class = $this->typhoonAttributes()->get(self::ATTRIBUTE_INSTANCE_OF, array())
    )
    {
      $specificClassObject = new ObjectType(array(
        ObjectType::ATTRIBUTE_INSTANCE_OF => $class,
      ));

      $primaryType = new Composite\AndType;
      $primaryType->addTyphoonType($specificClassObject);
      $primaryType->addTyphoonType($traversableObject);

      return $primaryType;
    }

    $primaryType = new Composite\OrType;
    $primaryType->addTyphoonType(new ArrayType);
    $primaryType->addTyphoonType($traversableObject);

    return $primaryType;
  }

  /**
   * @var array
   */
  static protected $attributeSignatures = array();

  /**
   * @var array
   */
  protected $attributes;
}
