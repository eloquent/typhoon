<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Attribute\Attributes;
use Ezzatron\Typhoon\Attribute\AttributeSignature;

class NodeType extends Dynamic\BaseDynamicType
{
  /**
   * @param Attributes|array|null $attributes
   */
  public function __construct($attributes = null)
  {
    $attributes = Attributes::adapt($attributes);
    parent::__construct($attributes);

    $streamAttributes = $attributes->values();
    $streamAttributes[StreamType::ATTRIBUTE_WRAPPER] = StreamType::WRAPPER_PLAINFILE;

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
    $attributeSignature[self::ATTRIBUTE_TYPE] = new StringType;
  }

  const ATTRIBUTE_TYPE = 'type';

  const TYPE_DIRECTORY = 'dir';
  const TYPE_FILE = 'STDIO';

  /**
   * @var StreamType
   */
  protected $innerType;
}