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
  /**
   * @param Type $type
   *
   * @return string
   */
  public function alias(Type $type)
  {
    foreach ($this->types as $alias => $thisType)
    {
      if ($type === $thisType) return $alias;
    }

    throw new UnregisteredType($type);
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

    if (!$type instanceof Type)
    {
      $parameter = new Parameter;
      $parameter->setType(new ObjectType(new String(__NAMESPACE__.'\Type')));

      throw new UnexpectedArgument($parameter, new Integer(1), $parameter);
    }

    $this->types[mb_strtolower($alias)] = $type;
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

  /**
   * @var array
   */
  protected $types = array();
}