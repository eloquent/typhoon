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

use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\Composite\OrType;
use Ezzatron\Typhoon\Type\DirectoryType;
use Ezzatron\Typhoon\Type\FileType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\StreamType;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\Traversable\TraversableType as TraversableTypeInterface;
use Ezzatron\Typhoon\Type\ResourceType;
use Ezzatron\Typhoon\Type\TraversableType;

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
      $traversable->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, get_class($value));

      if ($depth > 0)
      {
        $this->determineSubType($traversable, $value, new Integer($depth - 1));
      }

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