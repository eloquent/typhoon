<?php

namespace Typhoon\Primitive;

use Typhoon\Primitive;
use Typhoon\Type\Integer as IntegerType;

final class Integer extends Primitive
{
  /**
   * @return IntegerType
   */
  final public function type()
  {
    return new IntegerType;
  }
}