<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use ArrayAccess;
use Typhoon\Exception\NotImplemented;
use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;
use Typhoon\TypeRegistry\Exception\UnregisteredType;
use Typhoon\TypeRegistry\Exception\UnregisteredTypeAlias;

class TypeRegistry implements ArrayAccess
{
  public function __construct()
  {
    $this->registerDefaults();
  }

  public function registerDefaults()
  {
    $this[self::TYPE_ARRAY] = __NAMESPACE__.'\Type\ArrayType';
    $this[self::TYPE_BOOLEAN] = __NAMESPACE__.'\Type\Boolean';
    $this[self::TYPE_CALLBACK] = __NAMESPACE__.'\Type\Callback';
    $this[self::TYPE_INTEGER] = __NAMESPACE__.'\Type\Integer';
    $this[self::TYPE_MIXED] = __NAMESPACE__.'\Type\Mixed';
    $this[self::TYPE_NULL] = __NAMESPACE__.'\Type\Null';
    $this[self::TYPE_OBJECT] = __NAMESPACE__.'\Type\Object';
    $this[self::TYPE_STRING] = __NAMESPACE__.'\Type\String';
    $this[self::TYPE_TRAVERSABLE] = __NAMESPACE__.'\Type\Traversable';

    $this['bool'] = __NAMESPACE__.'\Type\Boolean';
    $this['callable'] = __NAMESPACE__.'\Type\Callback';
    $this['int'] = __NAMESPACE__.'\Type\Integer';
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
      throw new UnregisteredType(new String($type));
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
      throw new UnregisteredTypeAlias(new String($alias));
    }

    return $this->types[mb_strtolower($alias)];
  }

  /**
   * @param string $alias
   */
  public function offsetUnset($alias)
  {
    throw new NotImplemented(new String('Unset'));
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
  const TYPE_INTEGER = 'integer';
  const TYPE_MIXED = 'mixed';
  const TYPE_NULL = 'null';
  const TYPE_OBJECT = 'object';
  const TYPE_STRING = 'string';
  const TYPE_TRAVERSABLE = 'traversable';

  /**
   * @var array
   */
  protected $types = array();

  /**
   * @var array
   */
  protected $aliases = array();
}