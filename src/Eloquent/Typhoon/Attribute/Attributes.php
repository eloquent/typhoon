<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Attribute;

use Eloquent\Typhoon\Assertion\Exception\MissingAttributeException;
use Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException;
use Eloquent\Typhoon\Assertion\Exception\UnexpectedAttributeException;
use Eloquent\Typhoon\Assertion\Exception\UnsupportedAttributeException;
use Eloquent\Typhoon\Assertion\ParameterAssertion;
use Eloquent\Typhoon\Collection\Collection;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\ArrayType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Typhoon;

class Attributes extends Collection
{
  /**
   * @param Attributes|array|null $attributes
   *
   * @return Attributes
   */
  static public function adapt($attributes)
  {
    if ($attributes instanceof self)
    {
      return clone $attributes;
    }

    if (null === $attributes)
    {
      return new static;
    }

    $parameter = new Parameter;
    $parameter->setName(new String('attributes'));
    $parameter->setType(new ArrayType);

    $assertion = new ParameterAssertion;
    $assertion->setParameter($parameter);
    $assertion->setValue($attributes);
    $assertion->assert();

    return new static($attributes);
  }

  /**
   * @param AttributeSignature $signature
   */
  public function setSignature(AttributeSignature $signature)
  {
    $this->signature = $signature;
    $this->assert();
  }

  /**
   * @return AttributeSignature|null
   */
  public function signature()
  {
    if (!$this->signature)
    {
      return null;
    }

    return clone $this->signature;
  }

  public function finalize()
  {
    $this->finalized = true;
  }

  /**
   * @return boolean
   */
  public function finalized()
  {
    return $this->finalized;
  }

  /**
   * @param integer|string $key
   * @param mixed $value
   */
  public function set($key, $value)
  {
    if ($this->finalized)
    {
      throw new Exception\FinalizedException(
        new String($key)
      );
    }

    parent::set($key, $value);
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    if ($this->finalized)
    {
      throw new Exception\FinalizedException(
        new String($key)
      );
    }

    $this->assertKeyExists($key);

    if ($this->signature && $this->signature->isRequired($key))
    {
      if ($holderName = $this->signature->holderName())
      {
        $holderName = new String($holderName);
      }

      throw new MissingAttributeException(
        new String($key)
        , $this->valueType($key)
        , $holderName
      );
    }

    parent::remove($key);
  }

  public function __clone()
  {
    $this->finalized = false;
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

        throw new MissingAttributeException(
          new String($key)
          , $this->valueType($key)
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
        $e->type()
        , new String($key)
        , $e->expectedType()
        , $holderName
        , $e->typeRenderer()
        , $e
      );
    }
  }

  /**
   * @var AttributeSignature
   */
  protected $signature;

  /**
   * @var boolean
   */
  protected $finalized = false;
}
