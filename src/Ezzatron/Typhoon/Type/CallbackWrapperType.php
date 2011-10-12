<?php

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Attribute\AttributeSignature;

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
      $this->attributes->get(self::ATTRIBUTE_CALLBACK, array($this, 'defaultCallback'))
      , $arguments
    );
  }

  /**
   * @return boolean
   */
  protected function defaultCallback()
  {
    return false;
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature->set(self::ATTRIBUTE_CALLBACK, new CallbackType);
    $attributeSignature->set(self::ATTRIBUTE_ARGUMENTS, new ArrayType);
  }

  const ATTRIBUTE_CALLBACK = 'callback';
  const ATTRIBUTE_ARGUMENTS = 'arguments';
}