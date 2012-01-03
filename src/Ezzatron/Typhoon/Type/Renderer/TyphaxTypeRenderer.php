<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Renderer;

use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\Dynamic\DynamicType;
use Ezzatron\Typhoon\Type\Registry\Exception\UnregisteredTypeException;
use Ezzatron\Typhoon\Type\Traversable\TraversableType as TraversableTypeInterface;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\TraversableType;
use Ezzatron\Typhoon\Type\Type;

class TyphaxTypeRenderer extends TypeRenderer
{
  /**
   * @param Type $type
   *
   * @return string
   */
  public function render(Type $type)
  {
    $class = null;

    if ($type instanceof ObjectType)
    {
      $class = $type->typhoonAttributes()->get(ObjectType::ATTRIBUTE_INSTANCE_OF, null);
    }
    if ($type instanceof TraversableType)
    {
      $class = $type->typhoonAttributes()->get(TraversableType::ATTRIBUTE_INSTANCE_OF, null);
    }

    if ($class)
    {
      $rendered = $class;
    }
    else
    {
      $rendered = $this->renderAlias($type);
    }

    if (!$class && $type instanceof DynamicType)
    {
      $rendered .= $this->renderAttributes($type->typhoonAttributes());
    }
    if ($type instanceof TraversableTypeInterface)
    {
      $rendered .= $this->renderTraversable($type->typhoonKeyType(), $type->typhoonSubType());
    }

    return $rendered;
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  protected function renderAlias(Type $type)
  {
    try
    {
      return $this->typeRegistry()->aliasByType($type);
    }
    catch (UnregisteredTypeException $e) {}

    $attributes = new Attributes(array(
      self::UNREGISTERED_ATTRIBUTE_INSTANCE_OF => get_class($type),
    ));
    
    return self::UNREGISTERED.$this->renderAttributes($attributes);
  }

  /**
   * @param Attributes $attributes
   *
   * @return string
   */
  protected function renderAttributes(Attributes $attributes)
  {
    $rendered = '';

    foreach ($attributes as $key => $value)
    {
      if ($rendered)
      {
        $rendered .= self::TOKEN_ATTRIBUTE_SEPARATOR;
      }
      else
      {
        $rendered .= self::TOKEN_ATTRIBUTES_START;
      }

      $rendered .= $this->renderAttribute(new String($key), $value);
    }

    if ($rendered)
    {
      $rendered .= self::TOKEN_ATTRIBUTES_END;
    }

    return $rendered;
  }

  /**
   * @param String $key
   * @param mixed $value
   *
   * @return string
   */
  protected function renderAttribute(String $key, $value)
  {
    return
      $this->renderAttributeKey($key)
      .self::TOKEN_ATTRIBUTE_EQUALS
      .$this->renderAttributeValue($value)
    ;
  }

  /**
   * @param String $key
   *
   * @return string
   */
  protected function renderAttributeKey(String $key)
  {
    return $key->value();
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  protected function renderAttributeValue($value)
  {
    return var_export($value, true);
  }

  /**
   * @param Type $keyType
   * @param Type $subType
   *
   * @return string
   */
  protected function renderTraversable(Type $keyType, Type $subType)
  {
    if ($keyType instanceof MixedType && $subType instanceof MixedType)
    {
      return '';
    }

    $rendered = self::TOKEN_TRAVERSABLE_START;

    if (!$keyType instanceof MixedType)
    {
      $rendered .= $this->render($keyType).self::TOKEN_TRAVERSABLE_SEPARATOR;
    }

    $rendered .= $this->render($subType);
    $rendered .= self::TOKEN_TRAVERSABLE_END;

    return $rendered;
  }

  const TOKEN_ATTRIBUTES_START = '(';
  const TOKEN_ATTRIBUTES_END = ')';

  const TOKEN_ATTRIBUTE_EQUALS = '=';
  const TOKEN_ATTRIBUTE_SEPARATOR = ',';
  
  const TOKEN_TRAVERSABLE_START = '<';
  const TOKEN_TRAVERSABLE_SEPARATOR = ',';
  const TOKEN_TRAVERSABLE_END = '>';

  const UNREGISTERED = 'unregistered';
  const UNREGISTERED_ATTRIBUTE_INSTANCE_OF = 'instanceOf';
}
