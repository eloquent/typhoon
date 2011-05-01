<?php

namespace Typhoon\Scalar;

use Typhoon\Scalar;

final class String extends Scalar
{
  /**
   * @return \Typhoon\Type
   */
  final public function type()
  {
    return new \Typhoon\Type\String;
  }
}