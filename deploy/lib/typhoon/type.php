<?php

namespace Typhoon;

use Typhoon\Type\Exception\UnexpectedType;

abstract class Type
{
  /**
   * @param mixed $value
   */
  public function assert($value)
  {
    if ($this->check($value)) return $value;
    
    throw new UnexpectedType($this);
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