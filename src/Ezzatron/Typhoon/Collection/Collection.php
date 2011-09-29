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
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\StringType;
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
    $this->assertKeyGet($key);

    return isset($this->values[$key]);
  }

  /**
   * @param integer|string $key
   *
   * @return boolean
   */
  public function keyExists($key)
  {
    $this->assertKeyGet($key);

    return array_key_exists($key, $this->values);
  }

  /**
   * @param integer|string $key
   * @param mixed $value
   */
  public function set($key, $value)
  {
    $this->assertKeySet($key);
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

    return $this->values[$key];
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    $this->assertKeyGet($key);

    if (!array_key_exists($key, $this->values))
    {
      throw new Exception\UndefinedKeyException(new String((string)$key));
    }

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
    $this->remove($key);
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
   * @param mixed $value
   */
  protected function assertValue($key, $value)
  {
    $assertion = $this->typeAssertion();
    $assertion->setType($this->valueType($key));
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