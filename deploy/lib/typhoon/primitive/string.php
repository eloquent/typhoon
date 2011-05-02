<?php

namespace Typhoon\Primitive;

use Typhoon\Primitive;
use Typhoon\Type\String as StringType;

final class String extends Primitive
{
  /**
   * @return StringType
   */
  final public function type()
  {
    return new StringType;
  }
}