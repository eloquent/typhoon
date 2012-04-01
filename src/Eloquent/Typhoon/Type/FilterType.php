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
use Eloquent\Typhoon\Primitive\Boolean;
use Eloquent\Typhoon\Attribute\AttributeSignature;

class FilterType extends Dynamic\BaseDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    $attributes = $this->typhoonAttributes();
    $filter = $attributes->get(self::ATTRIBUTE_FILTER);
    $options = $attributes->get(self::ATTRIBUTE_OPTIONS, array());

    return true && filter_var($value, $filter, $options);
  }

  /**
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_FILTER()->value();
  }

  const ATTRIBUTE_FILTER = 'filter';
  const ATTRIBUTE_OPTIONS = 'options';

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature->set(self::ATTRIBUTE_FILTER, new IntegerType, new Boolean(true));

    $optionsArrayType = new ArrayType;
    $optionsArrayType->setTyphoonKeyType(new StringType);
    $optionsType = new Composite\OrType;
    $optionsType->addTyphoonType($optionsArrayType);
    $optionsType->addTyphoonType(new IntegerType);
    $attributeSignature->set(self::ATTRIBUTE_OPTIONS, $optionsType);
  }
}
