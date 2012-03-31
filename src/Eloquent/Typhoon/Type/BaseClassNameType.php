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
use ReflectionException;

abstract class BaseClassNameType extends Dynamic\BaseDynamicType
{
  public function __construct(Boolean $interface, array $attributes = null)
  {
    parent::__construct($attributes);

    $this->interface = $interface->value();
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

    try
    {
      $class = new ReflectionClass($value);
    }
    catch (ReflectionException $e)
    {
      return false;
    }

    if ($class->isInterface() !== $this->interface)
    {
      return false;
    }

    return $this->checkClass(
      $this->typhoonAttributes()
      , $class
    );
  }

  const ATTRIBUTE_IMPLEMENTS = 'implements';

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $arrayOfInterfaceType = new ArrayType;
    $arrayOfInterfaceType->setTyphoonSubType(new InterfaceNameType);
    $interfaceOrArrayOfInterfaceType = new Composite\OrType;
    $interfaceOrArrayOfInterfaceType->addTyphoonType(new InterfaceNameType);
    $interfaceOrArrayOfInterfaceType->addTyphoonType($arrayOfInterfaceType);

    $attributeSignature->set(self::ATTRIBUTE_IMPLEMENTS, $interfaceOrArrayOfInterfaceType);
  }

  /**
   * @param Attributes $attributes
   * @param ReflectionClass $class
   *
   * @return boolean
   */
  protected function checkClass(Attributes $attributes, ReflectionClass $class)
  {
    if ($attributes->keyExists(self::ATTRIBUTE_IMPLEMENTS))
    {
      $interfaces = $attributes->get(self::ATTRIBUTE_IMPLEMENTS, array());
      if (!is_array($interfaces))
      {
        $interfaces = array($interfaces);
      }
      
      foreach ($interfaces as $interface)
      {
        if (!$class->implementsInterface($interface))
        {
          return false;
        }
      }
    }

    return true;
  }

  /**
   * @var boolean
   */
  protected $interface;

  /**
   * @var StringType
   */
  protected $innerType;
}
