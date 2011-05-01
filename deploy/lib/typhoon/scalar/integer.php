<?php

namespace Typhoon\Scalar;

use Typhoon\Scalar;

final class Integer extends Scalar
{
  /**
   * @return \Typhoon\Type
   */
  final public function type()
  {
    return new \Typhoon\Type\Integer;
  }
}