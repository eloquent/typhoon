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
use Eloquent\Typhoon\Attribute\AttributeSignature;

class FileType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $streamAttributes = $attributes;
    $streamAttributes[StreamType::ATTRIBUTE_TYPE] = StreamType::TYPE_STDIO;

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
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_FILE()->value();
  }

  const ATTRIBUTE_LOCAL = 'local';
  const ATTRIBUTE_MODE = 'mode';
  const ATTRIBUTE_READ = 'read';
  const ATTRIBUTE_WRAPPER = 'wrapper';
  const ATTRIBUTE_WRITE = 'write';

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
    $attributeSignature->set(self::ATTRIBUTE_MODE, $stringOrArrayOfStringType);
    $attributeSignature->set(self::ATTRIBUTE_READ, new BooleanType);
    $attributeSignature->set(self::ATTRIBUTE_WRAPPER, $stringOrArrayOfStringType);
    $attributeSignature->set(self::ATTRIBUTE_WRITE, new BooleanType);
  }

  /**
   * @var StreamType
   */
  protected $innerType;
}
