<?php

namespace Typhoon;

use Typhoon\Type\Exception\UnexpectedType;

abstract class Type
{
  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function assert($value)
  {
    if ($this->check($value)) return $value;
    
    throw new UnexpectedType($value, $this);
  }

  /**
   * @param mixed value
   * 
   * @return boolean
   */
  abstract public function check($value);

  /**
   * @return string
   */
  abstract public function __toString();
}