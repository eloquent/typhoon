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
    $namespace = 'Ezzatron\Typhoon\Type';

    $this->set(self::TYPE_ARRAY, $namespace.'\ArrayType');
    $this->set(self::TYPE_BOOLEAN, $namespace.'\BooleanType');
    $this->set(self::TYPE_CALLBACK, $namespace.'\CallbackType');
    $this->set(self::TYPE_CALLBACK_WRAPPER, $namespace.'\CallbackWrapperType');
    $this->set(self::TYPE_DIRECTORY, $namespace.'\DirectoryType');
    $this->set(self::TYPE_FILE, $namespace.'\FileType');
    $this->set(self::TYPE_FILTER, $namespace.'\FilterType');
    $this->set(self::TYPE_FLOAT, $namespace.'\FloatType');
    $this->set(self::TYPE_INTEGER, $namespace.'\IntegerType');
    $this->set(self::TYPE_INTEGERABLE, $namespace.'\IntegerableType');
    $this->set(self::TYPE_KEY, $namespace.'\KeyType');
    $this->set(self::TYPE_MIXED, $namespace.'\MixedType');
    $this->set(self::TYPE_NULL, $namespace.'\NullType');
    $this->set(self::TYPE_NUMBER, $namespace.'\NumberType');
    $this->set(self::TYPE_NUMERIC, $namespace.'\NumericType');
    $this->set(self::TYPE_OBJECT, $namespace.'\ObjectType');
    $this->set(self::TYPE_PARAMETER, $namespace.'\ParameterType');
    $this->set(self::TYPE_RESOURCE, $namespace.'\ResourceType');
    $this->set(self::TYPE_SCALAR, $namespace.'\ScalarType');
    $this->set(self::TYPE_SOCKET, $namespace.'\SocketType');
    $this->set(self::TYPE_STREAM, $namespace.'\StreamType');
    $this->set(self::TYPE_STRING, $namespace.'\StringType');
    $this->set(self::TYPE_STRINGABLE, $namespace.'\StringableType');
    $this->set(self::TYPE_TRAVERSABLE, $namespace.'\TraversableType');
    $this->set(self::TYPE_TYPE, $namespace.'\TypeType');

    $this->set(self::ALIAS_BOOL, $this->get(self::TYPE_BOOLEAN));
    $this->set(self::ALIAS_CALLABLE, $this->get(self::TYPE_CALLBACK));
    $this->set(self::ALIAS_DOUBLE, $this->get(self::TYPE_FLOAT));
    $this->set(self::ALIAS_FLOATABLE, $this->get(self::TYPE_NUMERIC));
    $this->set(self::ALIAS_INT, $this->get(self::TYPE_INTEGER));
    $this->set(self::ALIAS_KEYABLE, $this->get(self::TYPE_KEY));
    $this->set(self::ALIAS_LONG, $this->get(self::TYPE_INTEGER));
    $this->set(self::ALIAS_REAL, $this->get(self::TYPE_FLOAT));
  }

  /**
   * @param String $class
   *
   * @return boolean
   */
  public function isRegistered(String $class)
  {
    return array_key_exists($class->value(), $this->aliases());
  }

  /**
   * @param String $class
   *
   * @return string
   */
  public function alias(String $class)
  {
    if (!$this->isRegistered($class))
    {
      throw new Exception\UnregisteredTypeException($class);
    }

    $aliases = $this->aliases();

    return $aliases[$class->value()];
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  public function aliasByType(Type $type)
  {
    return $this->alias(new String(get_class($type)));
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
    return new StringType;
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

      foreach ($this->values as $alias => $class)
      {
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
  const TYPE_FILTER = 'filter';
  const TYPE_FLOAT = 'float';
  const TYPE_INTEGER = 'integer';
  const TYPE_INTEGERABLE = 'integerable';
  const TYPE_KEY = 'key';
  const TYPE_MIXED = 'mixed';
  const TYPE_NULL = 'null';
  const TYPE_NUMBER = 'number';
  const TYPE_NUMERIC = 'numeric';
  const TYPE_OBJECT = 'object';
  const TYPE_PARAMETER = 'typhoon_parameter';
  const TYPE_RESOURCE = 'resource';
  const TYPE_SCALAR = 'scalar';
  const TYPE_SOCKET = 'socket';
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
