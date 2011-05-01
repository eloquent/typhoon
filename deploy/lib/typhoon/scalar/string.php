<?php

namespace Typhoon\Scalar;

final class String extends \Typhoon\Scalar
{
  /**
   * @return \Typhoon\Type
   */
  final public function type()
  {
    return new \Typhoon\Type\String;
  }
}