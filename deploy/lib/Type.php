<?php

namespace Typhoon;

abstract class Type
{
  /**
   * @return string
   */
  public function __toString()
  {
    return $this->string();
  }
  
  /**
   * @return string
   */
  abstract public function string();
  
  /**
   * @param mixed value
   * 
   * @return boolean
   */
  abstract public function check($value);
}