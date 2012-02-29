<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Eloquent\Typhoon\Assertion\ParameterAssertion;
use Eloquent\Typhoon\Type\Composite\OrType;
use Eloquent\Typhoon\Parameter\Parameter;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\Type;

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
   * @return array
   */
  public function values()
  {
    return $this->values;
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
   * @return boolean
   */
  public function isEmpty()
  {
    return $this->count() < 1;
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
    if (!$this->allowEmptyKeyForSet())
    {
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
   * @param Integer $index
   * @param String $parameterName
   */
  protected function assertKeyForSet($key, Integer $index = null, String $parameterName = null)
  {
    $this->assertKey($this->keySetType(), $key, $index, $parameterName);
  }

  /**
   * @param mixed $key
   * @param Integer $index
   * @param String $parameterName
   */
  protected function assertKeyForGet($key, Integer $index = null, String $parameterName = null)
  {
    $this->assertKey($this->keyGetType(), $key, $index, $parameterName);
  }

  /**
   * @param Type $type
   * @param mixed $key
   * @param Integer $index
   * @param String $parameterName
   */
  protected function assertKey(Type $type, $key, Integer $index = null, String $parameterName = null)
  {
    if (null === $index)
    {
      $index = new Integer(0);
    }
    if (null === $parameterName)
    {
      $parameterName = new String('key');
    }

    $this->assertParameter($type, $key, $index, $parameterName);
  }

  /**
   * @param mixed $key
   * @param mixed $value
   * @param Integer $index
   * @param String $parameterName
   */
  protected function assertValue($key, $value, Integer $index = null, String $parameterName = null)
  {
    if (null === $index)
    {
      $index = new Integer(1);
    }
    if (null === $parameterName)
    {
      $parameterName = new String('value');
    }

    $this->assertParameter($this->valueType($key), $value, $index, $parameterName);
  }

  /**
   * @param Type $type
   * @param mixed $value
   */
  protected function assertParameter(Type $type, $value, Integer $index, String $name)
  {
    $parameter = new Parameter;
    $parameter->setType($type);
    $parameter->setName($name);

    $assertion = $this->parameterAssertion($parameter, $value, $index);
    $assertion->assert();
  }

  /**
   * @param mixed $key
   */
  protected function normaliseKey(&$key) {}

  /**
   * @param Parameter $parameter
   * @param mixed $value
   * @param Integer $index
   * 
   * @return ParameterAssertion
   */
  protected function parameterAssertion(Parameter $parameter, $value, Integer $index)
  {
    $assertion = new ParameterAssertion;
    $assertion->setParameter($parameter);
    $assertion->setValue($value);
    $assertion->setIndex($index);

    return $assertion;
  }

  /**
   * @var array
   */
  protected $values = array();
}