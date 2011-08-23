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

use Typhoon\Type\ArrayType;
use Typhoon\Type\Boolean;
use Typhoon\Type\Float;
use Typhoon\Type\Integer;
use Typhoon\Type\Mixed;
use Typhoon\Type\Null;
use Typhoon\Type\Object;
use Typhoon\Type\String;
use Typhoon\Type\Traversable;

class TypeInspector
{
  /**
   * @param mixed $value
   *
   * @return Type
   */
  public function typeOf($value)
  {
    $scalars = array(
      new Null,
      new Boolean,
      new Integer,
      new Float,
      new String,
    );

    foreach ($scalars as $scalar)
    {
      if ($scalar->typhoonCheck($value))
      {
        return $scalar;
      }
    }

    $array = new ArrayType;
    if ($array->typhoonCheck($value))
    {
      return $array;
    }

    $traversable = new Traversable;
    if ($traversable->typhoonCheck($value))
    {
      return $traversable;
    }

    $object = new Object;
    if ($object->typhoonCheck($value))
    {
      $object->setTyphoonAttribute(Object::ATTRIBUTE_CLASS, get_class($value));

      return $object;
    }

    // This should theoretically never happen, and is only in place for
    // future-proofing, in case new types are added to PHP.

    // @codeCoverageIgnoreStart

    return new Mixed;

    // @codeCoverageIgnoreEnd
  }
}