<?php

namespace Typhoon\Primitive;

use Typhoon\Primitive;

final class String extends Primitive
{
  /**
   * @return \Typhoon\Type
   */
  final public function type()
  {
    return new \Typhoon\Type\String;
  }
}