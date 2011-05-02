<?php

namespace Typhoon;

use Typhoon\Scalar\String;
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

  public function setName(String $name)
  {
    $this->name = $name;
  }

  /**
   * @return String
   */
  public function name()
  {
    return $this->name;
  }

  public function setDescription(String $description)
  {
    $this->description = $description;
  }

  /**
   * @return String
   */
  public function description()
  {
    return $this->description;
  }

  /**
   * @var Type
   */
  protected $type;

  /**
   * @var String
   */
  protected $name;

  /**
   * @var String
   */
  protected $description;
}