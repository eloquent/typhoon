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
  public function __construct(array $attributes = null)
  {
    parent::__construct($attributes);

    $this->innerType = new ResourceType(array(
      ResourceType::ATTRIBUTE_TYPE => ResourceType::TYPE_STREAM,
    ));
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

    if (!$this->hasAttributes())
    {
      return true;
    }
    
    $local = $this->typhoonAttributes()->get(self::ATTRIBUTE_LOCAL, null);
    if (null !== $local)
    {
      if ($this->isLocal($value) != $local)
      {
        return false;
      }
    }

    $modes = $this->typhoonAttributes()->get(self::ATTRIBUTE_MODE, null);
    $types = $this->typhoonAttributes()->get(self::ATTRIBUTE_TYPE, array());
    $wrappers = $this->typhoonAttributes()->get(self::ATTRIBUTE_WRAPPER, null);

    if ($modes || $types || $wrappers)
    {
      $metaData = $this->getMetaData($value);

      $valid = true;
      if ($modes)
      {
        if (!is_array($modes))
        {
          $modes = array($modes);
        }

        $valid = in_array($metaData[self::META_DATA_MODE], $modes, true);
      }
      if ($valid && $types)
      {
        if (!is_array($types))
        {
          $types = array($types);
        }

        $valid = in_array($metaData[self::META_DATA_TYPE], $types, true);
      }
      if ($valid && $wrappers)
      {
        if (!is_array($wrappers))
        {
          $wrappers = array($wrappers);
        }

        $valid = in_array($metaData[self::META_DATA_WRAPPER], $wrappers, true);
      }

      return $valid;
    }

    return true;
  }

  /**
   * @param stream $stream
   * 
   * @return boolean
   */
  protected function isLocal($stream)
  {
    return stream_is_local($stream);
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
    $arrayOfStringType = new ArrayType;
    $arrayOfStringType->setTyphoonSubType(new StringType);
    $stringOrArrayOfStringType = new Composite\OrType;
    $stringOrArrayOfStringType->addTyphoonType(new StringType);
    $stringOrArrayOfStringType->addTyphoonType($arrayOfStringType);

    $attributeSignature->set(self::ATTRIBUTE_LOCAL, new BooleanType);
    $attributeSignature->set(self::ATTRIBUTE_MODE, $stringOrArrayOfStringType);
    $attributeSignature->set(self::ATTRIBUTE_TYPE, $stringOrArrayOfStringType);
    $attributeSignature->set(self::ATTRIBUTE_WRAPPER, $stringOrArrayOfStringType);
  }

  const ATTRIBUTE_LOCAL = 'local';
  const ATTRIBUTE_MODE = 'mode';
  const ATTRIBUTE_TYPE = 'type';
  const ATTRIBUTE_WRAPPER = 'wrapper';

  const META_DATA_MODE = 'mode';
  const META_DATA_TYPE = 'stream_type';
  const META_DATA_WRAPPER = 'wrapper_type';

  const TYPE_DIR = 'dir';
  const TYPE_STDIO = 'STDIO';
  const TYPE_TCP_SOCKET = 'tcp_socket';
  const TYPE_TCP_SOCKET_SSL = 'tcp_socket/ssl';
  
  const WRAPPER_PLAINFILE = 'plainfile';
  const WRAPPER_HTTP = 'http';

  /**
   * @var ResourceType
   */
  protected $innerType;
}