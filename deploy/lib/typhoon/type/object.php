<?php

namespace Typhoon\Type;

use Typhoon\Type;

class Object extends Type
{
  public function __construct($class = null)
  {
    $this->class = $class;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
  {
    if ($this->class) return $value instanceof $this->class;

    return is_object($value);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    $string = 'object';

    if ($this->class) $string .= '('.$this->class.')';

    return $string;
  }

  /**
   * @var string
   */
  protected $class;
}