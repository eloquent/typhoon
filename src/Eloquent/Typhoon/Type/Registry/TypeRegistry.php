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

    $this->setIntrinsic(IntrinsicTypeName::NAME_ARRAY()->value(), $namespace.'\ArrayType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_BOOLEAN()->value(), $namespace.'\BooleanType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_CALLBACK()->value(), $namespace.'\CallbackType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_CALLBACK_WRAPPER()->value(), $namespace.'\CallbackWrapperType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_CHARACTER()->value(), $namespace.'\CharacterType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_CLASS_NAME()->value(), $namespace.'\ClassNameType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_DIRECTORY()->value(), $namespace.'\DirectoryType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_FILE()->value(), $namespace.'\FileType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_FILTER()->value(), $namespace.'\FilterType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_FLOAT()->value(), $namespace.'\FloatType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_INTEGER()->value(), $namespace.'\IntegerType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_INTEGERABLE()->value(), $namespace.'\IntegerableType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_INTERFACE_NAME()->value(), $namespace.'\InterfaceNameType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_KEY()->value(), $namespace.'\KeyType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_MIXED()->value(), $namespace.'\MixedType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_NULL()->value(), $namespace.'\NullType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_NUMBER()->value(), $namespace.'\NumberType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_NUMERIC()->value(), $namespace.'\NumericType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_OBJECT()->value(), $namespace.'\ObjectType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_RESOURCE()->value(), $namespace.'\ResourceType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_SCALAR()->value(), $namespace.'\ScalarType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_SOCKET()->value(), $namespace.'\SocketType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_STREAM()->value(), $namespace.'\StreamType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_STRING()->value(), $namespace.'\StringType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_STRINGABLE()->value(), $namespace.'\StringableType');
    $this->setIntrinsic(IntrinsicTypeName::NAME_TRAVERSABLE()->value(), $namespace.'\TraversableType');

    foreach (IntrinsicTypeAlias::multitonInstances() as $key => $alias)
    {
      $this->setIntrinsic($alias->value(), $this->get($alias->typeName()));
    }

    $this->setIntrinsic('typhoon.parameter', $namespace.'\ParameterType');
    $this->setIntrinsic('typhoon.type', $namespace.'\TypeType');
  }

  /**
   * @param String $class
   *
   * @return boolean
   */
  public function isRegistered(String $class)
  {
    return array_key_exists($class->value(), $this->typeNames());
  }

  /**
   * @param String $class
   *
   * @return string
   */
  public function typeNameByClass(String $class)
  {
    if (!$this->isRegistered($class))
    {
      throw new Exception\UnregisteredTypeException($class);
    }

    $typeNames = $this->typeNames();

    return $typeNames[$class->value()];
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  public function typeNameByType(Type $type)
  {
    return $this->typeNameByClass(new String(get_class($type)));
  }

  /**
   * @param string $key
   * @param Type $value
   */
  public function set($key, $value)
  {
    $this->assertTypeNameNamespace(new String($key));
    $this->typeNames = NULL;

    parent::set($key, $value);
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    $this->typeNames = NULL;

    parent::remove($key);
  }

  const NAMESPACE_SEPARATOR = '.';

  /**
   * @param string $key
   * @param Type $value
   */
  protected function setIntrinsic($key, $value)
  {
    $this->typeNames = NULL;

    parent::set($key, $value);
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
   * @param String $key
   */
  protected function assertTypeNameNamespace(String $key)
  {
    $parts = explode(static::NAMESPACE_SEPARATOR, $key->value());

    if (
      count($parts) > 1
      && trim($parts[0])
    )
    {
      return;
    }

    throw new Exception\InvalidTypeNameNamespaceException($key);
  }

  /**
   * @param string $key
   */
  protected function assertKeyExists($key)
  {
    try
    {
      parent::assertKeyExists($key);
    }
    catch (UndefinedKeyException $e)
    {
      throw new Exception\UnregisteredTypeNameException(new String($key), $e);
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
  protected function typeNames()
  {
    if (null === $this->typeNames)
    {
      $this->typeNames = array();

      foreach ($this->values as $typeName => $class)
      {
        if (array_key_exists($class, $this->typeNames))
        {
          continue;
        }

        $this->typeNames[$class] = $typeName;
      }
    }

    return $this->typeNames;
  }

  /**
   * @var array
   */
  protected $typeNames;
}
