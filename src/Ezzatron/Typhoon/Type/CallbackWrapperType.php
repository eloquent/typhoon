<?php

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Attribute\AttributeSignature;
use Ezzatron\Typhoon\Primitive\Boolean;

class CallbackWrapperType extends Dynamic\BaseDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    $arguments = array_merge(
      array($value)
      , $this->attributes->get(self::ATTRIBUTE_ARGUMENTS, array())
    );

    return call_user_func_array(
      $this->attributes->get(self::ATTRIBUTE_CALLBACK)
      , $arguments
    );
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature->set(self::ATTRIBUTE_CALLBACK, new CallbackType, new Boolean(true));
    $attributeSignature->set(self::ATTRIBUTE_ARGUMENTS, new ArrayType);
  }

  const ATTRIBUTE_CALLBACK = 'callback';
  const ATTRIBUTE_ARGUMENTS = 'arguments';
}