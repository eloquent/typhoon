<?php

namespace Typhoon\Type;

use Typhoon\Type;

class Mixed extends Type
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
  {
    return true;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return 'mixed';
  }
}