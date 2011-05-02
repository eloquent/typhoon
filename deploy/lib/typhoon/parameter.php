<?php

namespace Typhoon;

use Typhoon\Scalar\String as StringScalar;
use Typhoon\Type\Mixed as MixedType;

class Parameter
{
  public function __construct()
  {
    $this->type = new MixedType;
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

  public function setName(StringScalar $name)
  {
    $this->name = $name;
  }

  /**
   * @return StringScalar
   */
  public function name()
  {
    return $this->name;
  }

  public function setDescription(StringScalar $description)
  {
    $this->description = $description;
  }

  /**
   * @return StringScalar
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
   * @var StringScalar
   */
  protected $name;

  /**
   * @var StringScalar
   */
  protected $description;
}