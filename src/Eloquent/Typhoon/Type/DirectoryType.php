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

use Eloquent\Typhoon\Attribute\AttributeSignature;

class DirectoryType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $streamAttributes = $attributes;
    $streamAttributes[StreamType::ATTRIBUTE_TYPE] = StreamType::TYPE_DIR;

    $this->innerType = new StreamType($streamAttributes);
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    return $this->innerType->typhoonCheck($value);
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $attributeSignature->set(self::ATTRIBUTE_LOCAL, new BooleanType);
    $attributeSignature->set(self::ATTRIBUTE_WRAPPER, $stringOrArrayOfStringType);
  }

  const ATTRIBUTE_LOCAL = 'local';
  const ATTRIBUTE_WRAPPER = 'wrapper';

  /**
   * @var StreamType
   */
  protected $innerType;
}
