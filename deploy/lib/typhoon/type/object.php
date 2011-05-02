<?php

namespace Typhoon\Type;

use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type;

class Object extends Type
{
  public function __construct(StringPrimitive $class = null)
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
    if ($this->class)
    {
      $class = (string)$this->class;
      
      return $value instanceof $class;
    }

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
   * @var StringPrimitive
   */
  protected $class;
}