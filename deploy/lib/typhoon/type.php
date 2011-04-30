<?php

namespace Typhoon;

abstract class Type
{
  /**
   * @param mixed $value
   */
  public function assert($value)
  {
    if ($this->check($value)) return $value;
    
    throw new Type\Exception\UnexpectedType;
  }
  
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