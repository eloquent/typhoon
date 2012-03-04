<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Enumeration;

use Eloquent\Typhoon\Exception\UndefinedMethodException;
use Eloquent\Typhoon\Primitive\String;
use ReflectionClass;

abstract class Enumeration
{
  /**
   * @return array
   */
  public static function values()
  {
    $class = get_called_class();
    if (__CLASS__ === $class) {
      throw new UndefinedMethodException(new String(__CLASS__), new String(__FUNCTION__));
    }

    if (!array_key_exists($class, self::$values)) {
      self::$values[$class] = self::_values($class);
    }

    return self::$values[$class];
  }

  /**
   * @return array
   */
  private static function _values($class)
  {
    $reflector = new ReflectionClass($class);

    return $reflector->getConstants();
  }

  // @codeCoverageIgnoreStart
  private function __construct() {}
  // @codeCoverageIgnoreEnd
  
  /**
   * @var array
   */
  private static $values = array();
}
