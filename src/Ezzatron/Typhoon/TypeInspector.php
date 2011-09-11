<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\Boolean;
use Ezzatron\Typhoon\Type\Float;
use Ezzatron\Typhoon\Type\Integer;
use Ezzatron\Typhoon\Type\Mixed;
use Ezzatron\Typhoon\Type\Null;
use Ezzatron\Typhoon\Type\Object;
use Ezzatron\Typhoon\Type\String;
use Ezzatron\Typhoon\Type\Resource;
use Ezzatron\Typhoon\Type\Traversable;

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
      new Resource,
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
      $object->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, get_class($value));

      return $object;
    }

    // This should theoretically never happen, and is only in place for
    // future-proofing, in case new types are added to PHP.

    // @codeCoverageIgnoreStart

    return new Mixed;

    // @codeCoverageIgnoreEnd
  }
}