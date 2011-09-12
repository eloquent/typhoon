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

use ArrayAccess;
use Ezzatron\Typhoon\Exception\NotImplementedException;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

class TypeRegistry implements ArrayAccess
{
  public function __construct()
  {
    $this->registerDefaults();
  }

  public function registerDefaults()
  {
    $this[self::TYPE_ARRAY] = 'Ezzatron\Typhoon\Type\ArrayType';
    $this[self::TYPE_BOOLEAN] = 'Ezzatron\Typhoon\Type\BooleanType';
    $this[self::TYPE_CALLBACK] = 'Ezzatron\Typhoon\Type\CallbackType';
    $this[self::TYPE_FLOAT] = 'Ezzatron\Typhoon\Type\FloatType';
    $this[self::TYPE_INTEGER] = 'Ezzatron\Typhoon\Type\IntegerType';
    $this[self::TYPE_MIXED] = 'Ezzatron\Typhoon\Type\MixedType';
    $this[self::TYPE_NULL] = 'Ezzatron\Typhoon\Type\NullType';
    $this[self::TYPE_OBJECT] = 'Ezzatron\Typhoon\Type\ObjectType';
    $this[self::TYPE_RESOURCE] = 'Ezzatron\Typhoon\Type\ResourceType';
    $this[self::TYPE_STRING] = 'Ezzatron\Typhoon\Type\StringType';
    $this[self::TYPE_TRAVERSABLE] = 'Ezzatron\Typhoon\Type\TraversableType';

    $this[self::ALIAS_BOOL] = 'Ezzatron\Typhoon\Type\BooleanType';
    $this[self::ALIAS_CALLABLE] = 'Ezzatron\Typhoon\Type\CallbackType';
    $this[self::ALIAS_DOUBLE] = 'Ezzatron\Typhoon\Type\FloatType';
    $this[self::ALIAS_INT] = 'Ezzatron\Typhoon\Type\IntegerType';
    $this[self::ALIAS_LONG] = 'Ezzatron\Typhoon\Type\IntegerType';
    $this[self::ALIAS_REAL] = 'Ezzatron\Typhoon\Type\FloatType';
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

    if (!isset($this->aliases[$type]))
    {
      throw new Exception\UnregisteredTypeException(new String($type));
    }

    return $this->aliases[$type];
  }

  /**
   * @param string $alias
   *
   * @return boolean
   */
  public function offsetExists($alias)
  {
    new String($alias);

    return isset($this->types[mb_strtolower($alias)]);
  }

  /**
   * @param string $alias
   * @param string $class
   */
  public function offsetSet($alias, $class)
  {
    new String($alias);
    new String($class);

    $this->types[mb_strtolower($alias)] = $class;
    $this->indexAliases();
  }

  /**
   * @param string $alias
   *
   * @return string
   */
  public function offsetGet($alias)
  {
    if (!$this->offsetExists($alias))
    {
      throw new Exception\UnregisteredTypeAliasException(new String($alias));
    }

    return $this->types[mb_strtolower($alias)];
  }

  /**
   * @param string $alias
   */
  public function offsetUnset($alias)
  {
    throw new NotImplementedException(new String('Unset'));
  }

  protected function indexAliases()
  {
    $this->aliases = array();

    foreach ($this->types as $alias => $class)
    {
      if (isset($this->aliases[$class]))
      {
        continue;
      }

      $this->aliases[$class] = $alias;
    }
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
  protected $types = array();

  /**
   * @var array
   */
  protected $aliases = array();
}