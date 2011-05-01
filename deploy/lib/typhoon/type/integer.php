<?php

namespace Typhoon\Type;

class Integer extends \Typhoon\Type
{
  /**
   * @return string
   */
  public function string()
  {
    return 'integer';
  }
  
  /**
   * @param mixed value
   * 
   * @return boolean
   */
  public function check($value)
  {
    return is_integer($value);
  }
}