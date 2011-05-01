<?php

namespace Typhoon\Type;

class Object extends \Typhoon\Type
{
  public function __construct($class = null)
  {
    $this->class = $class;
  }

  /**
   * @return string
   */
  public function string()
  {
    $string = 'object';
    
    if ($this->class) $string .= '('.$this->class.')';

    return $string;
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
   * @var string
   */
  protected $class;
}