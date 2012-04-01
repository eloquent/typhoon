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

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use Eloquent\Typhax\IntrinsicType\IntrinsicTypeAlias;
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

    $this->set(IntrinsicTypeName::NAME_ARRAY()->value(), $namespace.'\ArrayType');
    $this->set(IntrinsicTypeName::NAME_BOOLEAN()->value(), $namespace.'\BooleanType');
    $this->set(IntrinsicTypeName::NAME_CALLBACK()->value(), $namespace.'\CallbackType');
    $this->set(IntrinsicTypeName::NAME_CALLBACK_WRAPPER()->value(), $namespace.'\CallbackWrapperType');
    $this->set(IntrinsicTypeName::NAME_CHARACTER()->value(), $namespace.'\CharacterType');
    $this->set(IntrinsicTypeName::NAME_CLASS_NAME()->value(), $namespace.'\ClassNameType');
    $this->set(IntrinsicTypeName::NAME_DIRECTORY()->value(), $namespace.'\DirectoryType');
    $this->set(IntrinsicTypeName::NAME_FILE()->value(), $namespace.'\FileType');
    $this->set(IntrinsicTypeName::NAME_FILTER()->value(), $namespace.'\FilterType');
    $this->set(IntrinsicTypeName::NAME_FLOAT()->value(), $namespace.'\FloatType');
    $this->set(IntrinsicTypeName::NAME_INTEGER()->value(), $namespace.'\IntegerType');
    $this->set(IntrinsicTypeName::NAME_INTEGERABLE()->value(), $namespace.'\IntegerableType');
    $this->set(IntrinsicTypeName::NAME_INTERFACE_NAME()->value(), $namespace.'\InterfaceNameType');
    $this->set(IntrinsicTypeName::NAME_KEY()->value(), $namespace.'\KeyType');
    $this->set(IntrinsicTypeName::NAME_MIXED()->value(), $namespace.'\MixedType');
    $this->set(IntrinsicTypeName::NAME_NULL()->value(), $namespace.'\NullType');
    $this->set(IntrinsicTypeName::NAME_NUMBER()->value(), $namespace.'\NumberType');
    $this->set(IntrinsicTypeName::NAME_NUMERIC()->value(), $namespace.'\NumericType');
    $this->set(IntrinsicTypeName::NAME_OBJECT()->value(), $namespace.'\ObjectType');
    $this->set(IntrinsicTypeName::NAME_RESOURCE()->value(), $namespace.'\ResourceType');
    $this->set(IntrinsicTypeName::NAME_SCALAR()->value(), $namespace.'\ScalarType');
    $this->set(IntrinsicTypeName::NAME_SOCKET()->value(), $namespace.'\SocketType');
    $this->set(IntrinsicTypeName::NAME_STREAM()->value(), $namespace.'\StreamType');
    $this->set(IntrinsicTypeName::NAME_STRING()->value(), $namespace.'\StringType');
    $this->set(IntrinsicTypeName::NAME_STRINGABLE()->value(), $namespace.'\StringableType');
    $this->set(IntrinsicTypeName::NAME_TRAVERSABLE()->value(), $namespace.'\TraversableType');

    foreach (IntrinsicTypeAlias::instances() as $key => $alias)
    {
      $this->set($alias->value(), $this->get($alias->typeName()));
    }

    $this->set('typhoon.parameter', $namespace.'\ParameterType');
    $this->set('typhoon.type', $namespace.'\TypeType');
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

  /**
   * @var array
   */
  protected $aliases;
}
