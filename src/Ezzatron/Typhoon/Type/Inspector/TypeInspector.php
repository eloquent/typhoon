<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Inspector;

use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\DirectoryType;
use Ezzatron\Typhoon\Type\FileType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\StreamType;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\ResourceType;
use Ezzatron\Typhoon\Type\TraversableType;

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
      new NullType,
      new BooleanType,
      new IntegerType,
      new FloatType,
      new StringType,
      new FileType,
      new DirectoryType,
      new StreamType,
      new ResourceType,
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

    $traversable = new TraversableType;
    if ($traversable->typhoonCheck($value))
    {
      $traversable->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, get_class($value));

      return $traversable;
    }

    $object = new ObjectType;
    if ($object->typhoonCheck($value))
    {
      $object->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, get_class($value));

      return $object;
    }

    // This should theoretically never happen, and is only in place for
    // future-proofing, in case new types are added to PHP.

    // @codeCoverageIgnoreStart

    return new MixedType;

    // @codeCoverageIgnoreEnd
  }
}