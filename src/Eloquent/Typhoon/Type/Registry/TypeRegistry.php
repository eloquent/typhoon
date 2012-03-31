<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Registry;

use Eloquent\Typhoon\Collection\Collection;
use Eloquent\Typhoon\Collection\Exception\UndefinedKeyException;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\ArrayType;
use Eloquent\Typhoon\Type\BooleanType;
use Eloquent\Typhoon\Type\CallbackType;
use Eloquent\Typhoon\Type\CallbackWrapperType;
use Eloquent\Typhoon\Type\CharacterType;
use Eloquent\Typhoon\Type\ClassNameType;
use Eloquent\Typhoon\Type\DirectoryType;
use Eloquent\Typhoon\Type\FileType;
use Eloquent\Typhoon\Type\FilterType;
use Eloquent\Typhoon\Type\FloatType;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\IntegerableType;
use Eloquent\Typhoon\Type\InterfaceNameType;
use Eloquent\Typhoon\Type\KeyType;
use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\NumberType;
use Eloquent\Typhoon\Type\NumericType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\ParameterType;
use Eloquent\Typhoon\Type\ResourceType;
use Eloquent\Typhoon\Type\ScalarType;
use Eloquent\Typhoon\Type\SocketType;
use Eloquent\Typhoon\Type\StreamType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\StringableType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Type\TypeType;

class TypeRegistry extends Collection
{
  public function __construct()
  {
    $this->registerDefaults();
  }

  public function registerDefaults()
  {
    $namespace = 'Eloquent\Typhoon\Type';

    $this->set(ArrayType::ALIAS, $namespace.'\ArrayType');
    $this->set(BooleanType::ALIAS, $namespace.'\BooleanType');
    $this->set(CallbackType::ALIAS, $namespace.'\CallbackType');
    $this->set(CallbackWrapperType::ALIAS, $namespace.'\CallbackWrapperType');
    $this->set(CharacterType::ALIAS, $namespace.'\CharacterType');
    $this->set(ClassNameType::ALIAS, $namespace.'\ClassNameType');
    $this->set(DirectoryType::ALIAS, $namespace.'\DirectoryType');
    $this->set(FileType::ALIAS, $namespace.'\FileType');
    $this->set(FilterType::ALIAS, $namespace.'\FilterType');
    $this->set(FloatType::ALIAS, $namespace.'\FloatType');
    $this->set(IntegerType::ALIAS, $namespace.'\IntegerType');
    $this->set(IntegerableType::ALIAS, $namespace.'\IntegerableType');
    $this->set(InterfaceNameType::ALIAS, $namespace.'\InterfaceNameType');
    $this->set(KeyType::ALIAS, $namespace.'\KeyType');
    $this->set(MixedType::ALIAS, $namespace.'\MixedType');
    $this->set(NullType::ALIAS, $namespace.'\NullType');
    $this->set(NumberType::ALIAS, $namespace.'\NumberType');
    $this->set(NumericType::ALIAS, $namespace.'\NumericType');
    $this->set(ObjectType::ALIAS, $namespace.'\ObjectType');
    $this->set(ParameterType::ALIAS, $namespace.'\ParameterType');
    $this->set(ResourceType::ALIAS, $namespace.'\ResourceType');
    $this->set(ScalarType::ALIAS, $namespace.'\ScalarType');
    $this->set(SocketType::ALIAS, $namespace.'\SocketType');
    $this->set(StreamType::ALIAS, $namespace.'\StreamType');
    $this->set(StringType::ALIAS, $namespace.'\StringType');
    $this->set(StringableType::ALIAS, $namespace.'\StringableType');
    $this->set(TraversableType::ALIAS, $namespace.'\TraversableType');
    $this->set(TypeType::ALIAS, $namespace.'\TypeType');

    $this->set(self::ALIAS_BOOL, $this->get(BooleanType::ALIAS));
    $this->set(self::ALIAS_CALLABLE, $this->get(CallbackType::ALIAS));
    $this->set(self::ALIAS_DOUBLE, $this->get(FloatType::ALIAS));
    $this->set(self::ALIAS_FLOATABLE, $this->get(NumericType::ALIAS));
    $this->set(self::ALIAS_INT, $this->get(IntegerType::ALIAS));
    $this->set(self::ALIAS_KEYABLE, $this->get(ScalarType::ALIAS));
    $this->set(self::ALIAS_LONG, $this->get(IntegerType::ALIAS));
    $this->set(self::ALIAS_REAL, $this->get(FloatType::ALIAS));
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

  const ALIAS_BOOL = 'bool';
  const ALIAS_CALLABLE = 'callable';
  const ALIAS_DOUBLE = 'double';
  const ALIAS_FLOATABLE = 'floatable';
  const ALIAS_INT = 'int';
  const ALIAS_KEYABLE = 'keyable';
  const ALIAS_LONG = 'long';
  const ALIAS_REAL = 'real';

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
  
  /**
   * @var array
   */
  protected $aliases;
}
