<?php

namespace Typhoon\Scalar;

final class Integer extends \Typhoon\Scalar
{
  /**
   * @return \Typhoon\Type
   */
  final public function type()
  {
    return new \Typhoon\Type\Integer;
  }
}