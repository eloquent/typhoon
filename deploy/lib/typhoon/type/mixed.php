<?php

namespace Typhoon\Type;

use Typhoon\Type;

class Mixed extends Type
{
  /**
   * @return string
   */
  public function string()
  {
    return 'mixed';
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
  {
    return true;
  }
}