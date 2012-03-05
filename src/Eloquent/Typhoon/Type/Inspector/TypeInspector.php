<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Inspector;

use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Type\ArrayType;
use Eloquent\Typhoon\Type\BooleanType;
use Eloquent\Typhoon\Type\Composite\OrType;
use Eloquent\Typhoon\Type\DirectoryType;
use Eloquent\Typhoon\Type\FileType;
use Eloquent\Typhoon\Type\FloatType;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\MixedType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\SocketType;
use Eloquent\Typhoon\Type\StreamType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\SubTyped\TraversableType as TraversableTypeInterface;
use Eloquent\Typhoon\Type\ResourceType;
use Eloquent\Typhoon\Type\TraversableType;

class TypeInspector
{
  /**
   * @param mixed $value
   * @param Integer $depth
   *
   * @return Type
   */
  public function typeOf($value, Integer $depth = null)
  {
    if (null === $depth)
    {
      $depth = 0;
    }
    else
    {
      $depth = $depth->value();
    }

    $scalars = array(
      new NullType,
      new BooleanType,
      new IntegerType,
      new FloatType,
      new StringType,
      new FileType,
      new DirectoryType,
      new SocketType,
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
      if ($depth > 0)
      {
        $this->determineSubType($array, $value, new Integer($depth - 1));
      }

      return $array;
    }

    $traversable = new TraversableType;
    if ($traversable->typhoonCheck($value))
    {
      $traversable = new TraversableType(array(
        TraversableType::ATTRIBUTE_INSTANCE_OF => get_class($value),
      ));

      if ($depth > 0)
      {
        $this->determineSubType($traversable, $value, new Integer($depth - 1));
      }

      return $traversable;
    }

    $object = new ObjectType;
    if ($object->typhoonCheck($value))
    {
      $object = new ObjectType(array(
        ObjectType::ATTRIBUTE_INSTANCE_OF => get_class($value),
      ));

      return $object;
    }

    // This should theoretically never happen, and is only in place for
    // future-proofing, in case new types are added to PHP.

    // @codeCoverageIgnoreStart

    return new MixedType;

    // @codeCoverageIgnoreEnd
  }

  /**
   * @param TraversableTypeInterface $type
   * @param type $value
   * @param Integer $depth
   */
  protected function determineSubType(TraversableTypeInterface $type, $value, Integer $depth)
  {
    $keyTypes = array();
    $subTypes = array();
    $keyTypeCount = 0;
    $subTypeCount = 0;

    foreach ($value as $key => $subValue)
    {
      $keyType = $this->typeOf($key, $depth);
      $subType = $this->typeOf($subValue, $depth);

      if (!in_array($keyType, $keyTypes))
      {
        $keyTypes[] = $keyType;
        $keyTypeCount ++;
      }
      if (!in_array($subType, $subTypes))
      {
        $subTypes[] = $subType;
        $subTypeCount ++;
      }
    }

    if ($keyTypeCount > 0)
    {
      if ($keyTypeCount > 1)
      {
        $keyType = new OrType;
        array_map(array($keyType, 'addTyphoonType'), $keyTypes);
      }

      $type->setTyphoonKeyType($keyType);
    }
    if ($subTypeCount > 0)
    {
      if ($subTypeCount > 1)
      {
        $subType = new OrType;
        array_map(array($subType, 'addTyphoonType'), $subTypes);
      }

      $type->setTyphoonSubType($subType);
    }
  }
}
