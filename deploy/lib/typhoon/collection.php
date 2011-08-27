<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Typhoon;
use Typhoon\Assertion\Type as TypeAssertion;
use Typhoon\Collection\Exception\UndefinedKey;
use Typhoon\OrType;
use Typhoon\Primitive\Boolean;
use Typhoon\Primitive\String;
use Typhoon\Type\Boolean as BooleanType;
use Typhoon\Type\Integer as IntegerType;
use Typhoon\Type\Mixed as MixedType;
use Typhoon\Type\Null as NullType;
use Typhoon\Type\String as StringType;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
  /**
   * @param key $key
   * @return boolean
   */
  public function offsetExists($key)
  {
    $this->assertKeyGet($key);

    return isset($this->values[$key]);
  }

  /**
   * @param key $key
   * @param mixed $value
   */
  public function offsetSet($key, $value)
  {
    $this->assertKeySet($key);
    $this->assertValue($value);

    if (null === $key)
    {
      $this->values[] = $value;
    }
    else
    {
      $this->values[$key] = $value;
    }
  }

  /**
   * @param key $key
   *
   * @return Parameter
   */
  public function offsetGet($key)
  {
    $this->assertKeyGet($key);

    if (!array_key_exists($key, $this->values))
    {
      throw new UndefinedKey(new String((string)$key));
    }

    return $this->values[$key];
  }

  /**
   * @param key $key
   */
  public function offsetUnset($key)
  {
    $this->assertKeyGet($key);

    if (!array_key_exists($key, $this->values))
    {
      throw new UndefinedKey(new String((string)$key));
    }

    unset($this->values[$key]);
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->values);
  }

  /**
   * @return integer
   */
  public function count()
  {
    return count($this->values);
  }

  /**
   * @return Type
   */
  protected function keyType()
  {
    $keyType = new OrType;
    $keyType->addTyphoonType(new IntegerType);
    $keyType->addTyphoonType(new StringType);

    return $keyType;
  }

  /**
   * @return Type
   */
  protected function valueType()
  {
    return new MixedType;
  }

  /**
   * @param mixed $key
   * @param boolean $allowNull
   */
  protected function assertKey($key, $allowNull)
  {
    new Boolean($allowNull);

    if ($allowNull)
    {
      $type = new OrType;
      $type->addTyphoonType($this->keyType());
      $type->addTyphoonType(new NullType);
    }
    else
    {
      $type = $this->keyType();
    }

    $assertion = $this->typeAssertion();
    $assertion->setType($type);
    $assertion->setValue($key);

    $assertion->assert();
  }

  /**
   * @param mixed $key
   */
  protected function assertKeySet($key)
  {
    $this->assertKey($key, $this->allowEmptyKey());
  }

  /**
   * @param mixed $key
   */
  protected function assertKeyGet($key)
  {
    $this->assertKey($key, false);
  }

  /**
   * @param mixed $value
   */
  protected function assertValue($value)
  {
    $assertion = $this->typeAssertion();
    $assertion->setType($this->valueType());
    $assertion->setValue($value);

    $assertion->assert();
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKey()
  {
    return true;
  }

  /**
   * @return TypeAssertion
   */
  protected function typeAssertion()
  {
    return Typhoon::instance()->typeAssertion();
  }

  /**
   * @var array
   */
  protected $values = array();
}