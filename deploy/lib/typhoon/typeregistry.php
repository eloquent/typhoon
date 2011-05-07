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
use Typhoon\ParameterList\Exception\UnexpectedArgument;
use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;
use Typhoon\Type\Object as ObjectType;
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
    $this['boolean'] = __NAMESPACE__.'\Type\Boolean';
    $this['integer'] = __NAMESPACE__.'\Type\Integer';
    $this['mixed'] = __NAMESPACE__.'\Type\Mixed';
    $this['null'] = __NAMESPACE__.'\Type\Null';
    $this['object'] = __NAMESPACE__.'\Type\Object';
    $this['string'] = __NAMESPACE__.'\Type\String';
  }

  /**
   * @param Type|NULL $type
   *
   * @return string
   */
  public function alias($type)
  {
    if (is_object($type)) $type = get_class($type);
    if (!isset($this->aliases[$type])) throw new UnregisteredType(new String($type));

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
   * @param Type $type
   */
  public function offsetSet($alias, $type)
  {
    new String($alias);
    new String($type);

    $this->types[mb_strtolower($alias)] = $type;
    $this->indexAliases();
  }

  /**
   * @param string $alias
   *
   * @return Type
   */
  public function offsetGet($alias)
  {
    if (!$this->offsetExists($alias)) throw new UnregisteredTypeAlias(new String($alias));

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

    foreach ($this->types as $alias => $type)
    {
      if (isset($this->aliases[$type])) continue;

      $this->aliases[$type] = $alias;
    }
  }

  /**
   * @var array
   */
  protected $types = array();

  /**
   * @var array
   */
  protected $aliases = array();
}