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

    $read = $this->typhoonAttributes()->get(self::ATTRIBUTE_READ, null);
    $write = $this->typhoonAttributes()->get(self::ATTRIBUTE_WRITE, null);
    $modes = $this->typhoonAttributes()->get(self::ATTRIBUTE_MODE, array());
    $types = $this->typhoonAttributes()->get(self::ATTRIBUTE_TYPE, array());
    $wrappers = $this->typhoonAttributes()->get(self::ATTRIBUTE_WRAPPER, array());

    if (
      null !== $read
      || null !== $write
      || $modes
      || $types
      || $wrappers
    )
    {
      $metaData = $this->getMetaData($value);

      if (null !== $read)
      {
        if ($this->modeIsRead($metaData[self::META_DATA_MODE]) != $read)
        {
          return false;
        }
      }
      if (null !== $write)
      {
        if ($this->modeIsWrite($metaData[self::META_DATA_MODE]) != $write)
        {
          return false;
        }
      }

      $valid = true;

      if ($modes)
      {
        if (!is_array($modes))
        {
          $modes = array($modes);
        }

        $valid = false;
        foreach ($modes as $pattern)
        {
          if ($this->modeMatches($pattern, $metaData[self::META_DATA_MODE]))
          {
            $valid = true;

            break;
          }
        }
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
   * @return string
   */
  public function typhoonName()
  {
    return IntrinsicTypeName::NAME_STREAM()->value();
  }

  const ATTRIBUTE_LOCAL = 'local';
  const ATTRIBUTE_MODE = 'mode';
  const ATTRIBUTE_READ = 'read';
  const ATTRIBUTE_TYPE = 'type';
  const ATTRIBUTE_WRAPPER = 'wrapper';
  const ATTRIBUTE_WRITE = 'write';

  const META_DATA_MODE = 'mode';
  const META_DATA_TYPE = 'stream_type';
  const META_DATA_WRAPPER = 'wrapper_type';

  const MODE_APPEND = 'a';
  const MODE_BINARY = 'b';
  const MODE_CREATE = 'c';
  const MODE_NONEXISTANT = 'x';
  const MODE_PLUS = '+';
  const MODE_READ = 'r';
  const MODE_TEXT = 't';
  const MODE_WRITE = 'w';

  const TYPE_DIR = 'dir';
  const TYPE_STDIO = 'STDIO';
  const TYPE_TCP_SOCKET = 'tcp_socket';
  const TYPE_TCP_SOCKET_SSL = 'tcp_socket/ssl';

  const WRAPPER_PLAINFILE = 'plainfile';
  const WRAPPER_HTTP = 'http';

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
   * @param string $pattern
   * @param string $mode
   *
   * @return boolean
   */
  protected function modeMatches($pattern, $mode)
  {
    foreach (str_split($pattern) as $patternCharacter)
    {
      if (false === strpos($mode, $patternCharacter))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * @param string $mode
   *
   * @return boolean
   */
  protected function modeIsRead($mode)
  {
    $modes = array(
      self::MODE_READ,
      self::MODE_PLUS,
    );

    return true && preg_match('/['.preg_quote(implode($modes), '/').']/', $mode);
  }

  /**
   * @param string $mode
   *
   * @return boolean
   */
  protected function modeIsWrite($mode)
  {
    $modes = array(
      self::MODE_WRITE,
      self::MODE_PLUS,
      self::MODE_APPEND,
      self::MODE_NONEXISTANT,
      self::MODE_CREATE,
    );

    return true && preg_match('/['.preg_quote(implode($modes), '/').']/', $mode);
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
    $attributeSignature->set(self::ATTRIBUTE_READ, new BooleanType);
    $attributeSignature->set(self::ATTRIBUTE_TYPE, $stringOrArrayOfStringType);
    $attributeSignature->set(self::ATTRIBUTE_WRAPPER, $stringOrArrayOfStringType);
    $attributeSignature->set(self::ATTRIBUTE_WRITE, new BooleanType);
  }

  /**
   * @var ResourceType
   */
  protected $innerType;
}
