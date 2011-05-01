<?php

namespace Typhoon\Type;

class Mixed extends \Typhoon\Type
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