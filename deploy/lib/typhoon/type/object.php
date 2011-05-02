<?php

namespace Typhoon\Type;

use Typhoon\Scalar\String as StringScalar;
use Typhoon\Type;

class Object extends Type
{
  public function __construct(StringScalar $class = null)
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
   * @var StringScalar
   */
  protected $class;
}