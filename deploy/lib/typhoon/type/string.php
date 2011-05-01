<?php

namespace Typhoon\Type;

use Typhoon\Type;

class String extends Type
{
  /**
   * @return string
   */
  public function string()
  {
    return 'string';
  }
  
  /**
   * @param mixed value
   * 
   * @return boolean
   */
  public function check($value)
  {
    return is_string($value);
  }
}