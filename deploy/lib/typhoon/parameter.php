<?php

namespace Typhoon;

class Parameter
{
  public function __construct()
  {
    $this->type = new Type\Mixed;
  }

  public function setType(Type $type)
  {
    $this->type = $type;
  }

  /**
   * @return Type
   */
  public function type()
  {
    return $this->type;
  }

  /**
   * @var Type
   */
  protected $type;
}