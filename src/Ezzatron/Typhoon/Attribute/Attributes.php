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

use Ezzatron\Typhoon\Collection\Collection;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\Type;

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
        $holder = $this->signature->holder();
        if (null !== $holder)
        {
          $holder = new String($holder);
        }

        throw new Exception\RequiredAttributeException(
          new String($key)
          , $holder
        );
    }

    parent::remove($key);
  }

  /**
   * @return Type
   */
  protected function keyType()
  {
    return new StringType;
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
   */
  protected function assertKey(Type $type, $key)
  {
    parent::assertKey($type, $key);

    if (!$this->signature)
    {
      return;
    }

    if (!$this->signature->keyExists($key))
    {
      $holder = $this->signature->holder();
      if (null !== $holder)
      {
        $holder = new String($holder);
      }

      throw new Exception\UnsupportedAttributeException(
        new String($key)
        , $holder
      );
    }
  }

  protected function assert()
  {
    foreach ($this->signature as $key => $type)
    {
      if ($this->signature->isRequired($key) && !$this->keyExists($key))
      {
        $holder = $this->signature->holder();
        if (null !== $holder)
        {
          $holder = new String($holder);
        }

        throw new Exception\RequiredAttributeException(
          new String($key)
          , $holder
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
   * @var AttributeSignature
   */
  protected $signature;
}