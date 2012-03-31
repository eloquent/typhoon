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

class CharacterType extends Dynamic\BaseDynamicType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerStringType = new StringType($attributes);
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    if (!$this->innerStringType->typhoonCheck($value))
    {
      return false;
    }

    if (
      $this->hasAttributes()
      && $encodings = $this->typhoonAttributes()->get(self::ATTRIBUTE_ENCODING, array())
    )
    {
      if (!is_array($encodings))
      {
        $encodings = array($encodings);
      }
      foreach ($encodings as $encoding)
      {
        if (1 === mb_strlen($value, $encoding))
        {
          return true;
        }
      }

      return false;
    }

    return 1 === mb_strlen($value);
  }

  const ALIAS = 'character';

  const ATTRIBUTE_ENCODING = 'encoding';

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

    $attributeSignature->set(self::ATTRIBUTE_ENCODING, $stringOrArrayOfStringType);
  }

  /**
   * @var StringType
   */
  protected $innerStringType;
}
