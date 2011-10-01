<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Ezzatron\Typhoon\Assertion\TypeAssertion;
use Ezzatron\Typhoon\Type\Composite\OrType;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\Type;
use Ezzatron\Typhoon\Typhoon;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
  public function __construct(array $values = null)
  {
    if (null === $values)
    {
      $values = array();
    }

    foreach ($values as $key => $value)
    {
      $this->set($key, $value);
    }
  }

  /**
   * @param integer|string $key
   *
   * @return boolean
   */
  public function exists($key)
  {
    $this->assertKeyForGet($key);
    $this->normaliseKey($key);

    return isset($this->values[$key]);
  }

  /**
   * @param integer|string $key
   *
   * @return boolean
   */
  public function keyExists($key)
  {
    $this->assertKeyForGet($key);
    $this->normaliseKey($key);

    return array_key_exists($key, $this->values);
  }

  /**
   * @param integer|string $key
   * @param mixed $value
   */
  public function set($key, $value)
  {
    $this->assertKeyForSet($key);
    $this->normaliseKey($key);
    $this->assertValue($key, $value);

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
   * @param integer|string $key
   * @param mixed $default
   *
   * @return mixed
   */
  public function get($key, $default = null)
  {
    if (1 == func_num_args())
    {
      $this->assertKeyExists($key);
    }

    if (!$this->keyExists($key))
    {
      return $default;
    }

    $this->normaliseKey($key);

    return $this->values[$key];
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    $this->assertKeyExists($key);
    $this->normaliseKey($key);

    unset($this->values[$key]);
  }

  /**
   * @param integer|string $key
   *
   * @return boolean
   */
  public function offsetExists($key)
  {
    return $this->exists($key);
  }

  /**
   * @param integer|string $key
   * @param mixed $value
   */
  public function offsetSet($key, $value)
  {
    $this->set($key, $value);
  }

  /**
   * @param integer|string $key
   *
   * @return Parameter
   */
  public function offsetGet($key)
  {
    return $this->get($key);
  }

  /**
   * @param integer|string $key
   */
  public function offsetUnset($key)
  {
    try
    {
      $this->remove($key);
    }
    catch (Exception\UndefinedKeyException $e) {}
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
  protected function keyGetType()
  {
    return $this->keyType();
  }

  /**
   * @return Type
   */
  protected function keySetType()
  {
    if (!$this->allowEmptyKeyForSet()) {
      return $this->keyType();
    }

    $type = new OrType;
    $type->addTyphoonType($this->keyType());
    $type->addTyphoonType(new NullType);

    return $type;
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKeyForSet()
  {
    return true;
  }

  /**
   * @param mixed $key
   *
   * @return Type
   */
  protected function valueType($key)
  {
    return new MixedType;
  }

  /**
   * @param mixed $key
   */
  protected function assertKeyExists($key)
  {
    if (!$this->keyExists($key))
    {
      throw new Exception\UndefinedKeyException(new String((string)$key));
    }
  }

  /**
   * @param mixed $key
   */
  protected function assertKeyForSet($key)
  {
    $this->assertKey($this->keySetType(), $key);
  }

  /**
   * @param mixed $key
   */
  protected function assertKeyForGet($key)
  {
    $this->assertKey($this->keyGetType(), $key);
  }

  /**
   * @param Type $type
   * @param mixed $key
   */
  protected function assertKey(Type $type, $key)
  {
    $this->assertType($type, $key);
  }

  /**
   * @param mixed $key
   * @param mixed $value
   */
  protected function assertValue($key, $value)
  {
    $this->assertType($this->valueType($key), $value);
  }

  /**
   * @param Type $type
   * @param mixed $value
   */
  protected function assertType(Type $type, $value)
  {
    $assertion = $this->typeAssertion();
    $assertion->setType($type);
    $assertion->setValue($value);

    $assertion->assert();
  }

  /**
   * @param mixed $key
   */
  protected function normaliseKey(&$key) {}

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