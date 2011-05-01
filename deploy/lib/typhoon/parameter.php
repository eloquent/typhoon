<?php

namespace Typhoon;

use Typhoon\Type\Mixed;

class Parameter
{
  public function __construct()
  {
    $this->type = new Mixed;
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