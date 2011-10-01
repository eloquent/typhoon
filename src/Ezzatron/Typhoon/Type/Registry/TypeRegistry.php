<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Registry;

use Ezzatron\Typhoon\Collection\Collection;
use Ezzatron\Typhoon\Collection\Exception\UndefinedKeyException;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\CallbackType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\ResourceType;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\TraversableType;
use Ezzatron\Typhoon\Type\Type;
use Ezzatron\Typhoon\Type\TypeType;

class TypeRegistry extends Collection
{
  public function __construct()
  {
    $this->registerDefaults();
  }

  public function registerDefaults()
  {
    $this[self::TYPE_ARRAY] = new ArrayType;;
    $this[self::TYPE_BOOLEAN] = new BooleanType;
    $this[self::TYPE_CALLBACK] = new CallbackType;
    $this[self::TYPE_FLOAT] = new FloatType;
    $this[self::TYPE_INTEGER] = new IntegerType;
    $this[self::TYPE_MIXED] = new MixedType;
    $this[self::TYPE_NULL] = new NullType;
    $this[self::TYPE_OBJECT] = new ObjectType;
    $this[self::TYPE_RESOURCE] = new ResourceType;
    $this[self::TYPE_STRING] = new StringType;
    $this[self::TYPE_TRAVERSABLE] = new TraversableType;

    $this[self::ALIAS_BOOL] = $this[self::TYPE_BOOLEAN];
    $this[self::ALIAS_CALLABLE] = $this[self::TYPE_CALLBACK];
    $this[self::ALIAS_DOUBLE] = $this[self::TYPE_FLOAT];
    $this[self::ALIAS_INT] = $this[self::TYPE_INTEGER];
    $this[self::ALIAS_LONG] = $this[self::TYPE_INTEGER];
    $this[self::ALIAS_REAL] = $this[self::TYPE_FLOAT];
  }

  /**
   * @param Type|string $type
   *
   * @return string
   */
  public function alias($type)
  {
    if (is_object($type))
    {
      $type = get_class($type);
    }

    $aliases = $this->aliases();

    if (!array_key_exists($type, $aliases))
    {
      throw new Exception\UnregisteredTypeException(new String($type));
    }

    return $aliases[$type];
  }

  /**
   * @param integer|string $key
   * @param mixed $value
   */
  public function set($key, $value)
  {
    $this->aliases = NULL;

    parent::set($key, $value);
  }

  /**
   * @param integer|string $key
   * @param mixed $default
   *
   * @return mixed
   */
  public function get($key, $default = null)
  {
    $type = call_user_func_array(array('parent', 'get'), func_get_args());

    if ($type instanceof Type)
    {
      return clone $type;
    }
    
    return $type;
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    $this->aliases = NULL;

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
    return new TypeType;
  }

  /**
   * @param mixed $key
   */
  protected function assertKeyExists($key)
  {
    try
    {
      parent::assertKeyExists($key);
    }
    catch (UndefinedKeyException $e)
    {
      throw new Exception\UnregisteredTypeAliasException(new String($key), $e);
    }
  }

  /**
   * @param mixed $key
   */
  protected function normaliseKey(&$key)
  {
    $key = mb_strtolower($key);
  }

  /**
   * @return array
   */
  protected function aliases()
  {
    if (null === $this->aliases)
    {
      $this->aliases = array();

      foreach ($this->values as $alias => $type)
      {
        $class = get_class($type);

        if (isset($this->aliases[$class]))
        {
          continue;
        }

        $this->aliases[$class] = $alias;
      }
    }

    return $this->aliases;
  }

  const TYPE_ARRAY = 'array';
  const TYPE_BOOLEAN = 'boolean';
  const TYPE_CALLBACK = 'callback';
  const TYPE_FLOAT = 'float';
  const TYPE_INTEGER = 'integer';
  const TYPE_MIXED = 'mixed';
  const TYPE_NULL = 'null';
  const TYPE_OBJECT = 'object';
  const TYPE_RESOURCE = 'resource';
  const TYPE_STRING = 'string';
  const TYPE_TRAVERSABLE = 'traversable';

  const ALIAS_BOOL = 'bool';
  const ALIAS_CALLABLE = 'callable';
  const ALIAS_DOUBLE = 'double';
  const ALIAS_INT = 'int';
  const ALIAS_LONG = 'long';
  const ALIAS_REAL = 'real';
  
  /**
   * @var array
   */
  protected $aliases;
}