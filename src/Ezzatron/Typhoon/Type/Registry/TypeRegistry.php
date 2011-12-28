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
use Ezzatron\Typhoon\Type\CallbackWrapperType;
use Ezzatron\Typhoon\Type\DirectoryType;
use Ezzatron\Typhoon\Type\FileType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\IntegerableType;
use Ezzatron\Typhoon\Type\KeyType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\FilesystemNodeType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\NumberType;
use Ezzatron\Typhoon\Type\NumericType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\ParameterType;
use Ezzatron\Typhoon\Type\ResourceType;
use Ezzatron\Typhoon\Type\ScalarType;
use Ezzatron\Typhoon\Type\StreamType;
use Ezzatron\Typhoon\Type\StringableType;
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
    $this[self::TYPE_ARRAY] = new ArrayType;
    $this[self::TYPE_BOOLEAN] = new BooleanType;
    $this[self::TYPE_CALLBACK] = new CallbackType;
    $this[self::TYPE_CALLBACK_WRAPPER] = new CallbackWrapperType;
    $this[self::TYPE_DIRECTORY] = new DirectoryType;
    $this[self::TYPE_FILE] = new FileType;
    $this[self::TYPE_FLOAT] = new FloatType;
    $this[self::TYPE_INTEGER] = new IntegerType;
    $this[self::TYPE_INTEGERABLE] = new IntegerableType;
    $this[self::TYPE_KEY] = new KeyType;
    $this[self::TYPE_MIXED] = new MixedType;
    $this[self::TYPE_FILESYSTEM_NODE] = new FilesystemNodeType;
    $this[self::TYPE_NULL] = new NullType;
    $this[self::TYPE_NUMBER] = new NumberType;
    $this[self::TYPE_NUMERIC] = new NumericType;
    $this[self::TYPE_OBJECT] = new ObjectType;
    $this[self::TYPE_PARAMETER] = new ParameterType;
    $this[self::TYPE_RESOURCE] = new ResourceType;
    $this[self::TYPE_SCALAR] = new ScalarType;
    $this[self::TYPE_STREAM] = new StreamType;
    $this[self::TYPE_STRING] = new StringType;
    $this[self::TYPE_STRINGABLE] = new StringableType;
    $this[self::TYPE_TRAVERSABLE] = new TraversableType;
    $this[self::TYPE_TYPE] = new TypeType;

    $this[self::ALIAS_BOOL] = $this[self::TYPE_BOOLEAN];
    $this[self::ALIAS_CALLABLE] = $this[self::TYPE_CALLBACK];
    $this[self::ALIAS_DOUBLE] = $this[self::TYPE_FLOAT];
    $this[self::ALIAS_FLOATABLE] = $this[self::TYPE_NUMERIC];
    $this[self::ALIAS_INT] = $this[self::TYPE_INTEGER];
    $this[self::ALIAS_KEYABLE] = $this[self::TYPE_KEY];
    $this[self::ALIAS_LONG] = $this[self::TYPE_INTEGER];
    $this[self::ALIAS_REAL] = $this[self::TYPE_FLOAT];
  }

  /**
   * @param object|string $type
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

        if (array_key_exists($class, $this->aliases))
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
  const TYPE_CALLBACK_WRAPPER = 'callback_wrapper';
  const TYPE_DIRECTORY = 'directory';
  const TYPE_FILE = 'file';
  const TYPE_FLOAT = 'float';
  const TYPE_INTEGER = 'integer';
  const TYPE_INTEGERABLE = 'integerable';
  const TYPE_KEY = 'key';
  const TYPE_MIXED = 'mixed';
  const TYPE_FILESYSTEM_NODE = 'filesystem_node';
  const TYPE_NULL = 'null';
  const TYPE_NUMBER = 'number';
  const TYPE_NUMERIC = 'numeric';
  const TYPE_OBJECT = 'object';
  const TYPE_PARAMETER = 'typhoon_parameter';
  const TYPE_RESOURCE = 'resource';
  const TYPE_SCALAR = 'scalar';
  const TYPE_STREAM = 'stream';
  const TYPE_STRING = 'string';
  const TYPE_STRINGABLE = 'stringable';
  const TYPE_TRAVERSABLE = 'traversable';
  const TYPE_TYPE = 'typhoon_type';

  const ALIAS_BOOL = 'bool';
  const ALIAS_CALLABLE = 'callable';
  const ALIAS_DOUBLE = 'double';
  const ALIAS_FLOATABLE = 'floatable';
  const ALIAS_INT = 'int';
  const ALIAS_KEYABLE = 'keyable';
  const ALIAS_LONG = 'long';
  const ALIAS_REAL = 'real';
  
  /**
   * @var array
   */
  protected $aliases;
}