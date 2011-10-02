<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute;

use Ezzatron\Typhoon\Assertion\Exception\MissingAttributeException;
use Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException;
use Ezzatron\Typhoon\Assertion\Exception\UnexpectedAttributeException;
use Ezzatron\Typhoon\Assertion\Exception\UnsupportedAttributeException;
use Ezzatron\Typhoon\Collection\Collection;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\SimpleStringType;
use Ezzatron\Typhoon\Type\Type;
use Ezzatron\Typhoon\Typhoon;

class Attributes extends Collection
{
  /**
   * @param AttributeSignature $signature
   */
  public function setSignature(AttributeSignature $signature)
  {
    $this->signature = $signature;
    $this->assert();
  }

  /**
   * @return AttributeSignature
   */
  public function signature()
  {
    if (!$this->signature)
    {
      return null;
    }
    
    return clone $this->signature;
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    $this->assertKeyExists($key);
    
    if ($this->signature && $this->signature->isRequired($key))
    {
      if ($holderName = $this->signature->holderName())
      {
        $holderName = new String($holderName);
      }

      $expectedTypeName = Typhoon::instance()->typeRenderer()->render(
        $this->valueType($key)
      );

      throw new MissingAttributeException(
        new String($key)
        , new String((string)$expectedTypeName)
        , $holderName
      );
    }

    parent::remove($key);
  }

  /**
   * @return Type
   */
  protected function keyType()
  {
    return new SimpleStringType;
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKeyForSet()
  {
    return false;
  }

  /**
   * @param mixed $key
   *
   * @return Type
   */
  protected function valueType($key)
  {
    if (!$this->signature)
    {
      return parent::valueType($key);
    }

    return $this->signature[$key];
  }
  
  /**
   * @param Type $type
   * @param mixed $key
   * @param Integer $index
   * @param String $parameterName
   */
  protected function assertKey(Type $type, $key, Integer $index = null, String $parameterName = null)
  {
    parent::assertKey($type, $key, $index, $parameterName);

    if (!$this->signature)
    {
      return;
    }

    if (!$this->signature->keyExists($key))
    {
      if ($holderName = $this->signature->holderName())
      {
        $holderName = new String($holderName);
      }
      
      throw new UnsupportedAttributeException(
        new String($key)
        , $holderName
      );
    }
  }

  protected function assert()
  {
    foreach ($this->signature as $key => $type)
    {
      if ($this->signature->isRequired($key) && !$this->keyExists($key))
      {
        if ($holderName = $this->signature->holderName())
        {
          $holderName = new String($holderName);
        }

        $expectedTypeName = Typhoon::instance()->typeRenderer()->render(
          $this->valueType($key)
        );

        throw new MissingAttributeException(
          new String($key)
          , new String((string)$expectedTypeName)
          , $holderName
        );
      }
    }

    foreach ($this->values as $key => $value)
    {
      $this->assertKeyForGet($key);
      $this->assertValue($key, $value);
    }
  }

  /**
   * @param mixed $key
   * @param mixed $value
   * @param Integer $index
   * @param String $parameterName
   */
  protected function assertValue($key, $value, Integer $index = null, String $parameterName = null)
  {
    try
    {
      parent::assertValue($key, $value, $index, $parameterName);
    }
    catch (UnexpectedArgumentException $e)
    {
      if ($holderName = $this->signature->holderName())
      {
        $holderName = new String($holderName);
      }

      throw new UnexpectedAttributeException(
        new String($e->typeName())
        , new String($key)
        , new String($e->expectedTypeName())
        , $holderName
        , $e
      );
    }
  }

  /**
   * @var AttributeSignature
   */
  protected $signature;
}