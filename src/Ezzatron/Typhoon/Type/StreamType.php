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

class StreamType extends Dynamic\BaseDynamicType
{
  public function __construct(Attributes $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerType = new ResourceType(new Attributes(array(
      ResourceType::ATTRIBUTE_TYPE => ResourceType::TYPE_STREAM,
    )));
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

    $mode = $this->typhoonAttributes()->get(self::ATTRIBUTE_MODE, null);
    $type = $this->typhoonAttributes()->get(self::ATTRIBUTE_TYPE, null);
    $wrapper = $this->typhoonAttributes()->get(self::ATTRIBUTE_WRAPPER, null);

    if ($mode || $type || $wrapper)
    {
      $metaData = $this->getMetaData($value);

      $valid = true;
      if ($mode)
      {
        $valid = $mode == $metaData[self::META_DATA_MODE];
      }
      if ($valid && $type)
      {
        $valid = $type == $metaData[self::META_DATA_TYPE];
      }
      if ($valid && $wrapper)
      {
        $valid = $wrapper == $metaData[self::META_DATA_WRAPPER];
      }

      return $valid;
    }

    return true;
  }

  /**
   * @param stream $stream
   * 
   * @return array
   */
  protected function getMetaData($stream)
  {
    return stream_get_meta_data($stream);
  }

  /**
   * @param AttributeSignature $attributeSignature
   * @param BaseDynamicType $type
   *
   * @return AttributeSignature
   */
  static protected function configureAttributeSignature(AttributeSignature $attributeSignature, Dynamic\BaseDynamicType $type)
  {
    $attributeSignature[self::ATTRIBUTE_MODE] = new StringType;
    $attributeSignature[self::ATTRIBUTE_TYPE] = new StringType;
    $attributeSignature[self::ATTRIBUTE_WRAPPER] = new StringType;
  }

  const ATTRIBUTE_MODE = 'mode';
  const ATTRIBUTE_TYPE = 'type';
  const ATTRIBUTE_WRAPPER = 'wrapper';

  const META_DATA_MODE = 'mode';
  const META_DATA_TYPE = 'stream_type';
  const META_DATA_WRAPPER = 'wrapper_type';

  /**
   * @var ResourceType
   */
  protected $innerType;
}