<?php

namespace Typhoon;

use Typhoon\Primitive\String as StringPrimitive;
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

  public function setName(StringPrimitive $name)
  {
    $this->name = $name;
  }

  /**
   * @return StringPrimitive
   */
  public function name()
  {
    return $this->name;
  }

  public function setDescription(StringPrimitive $description)
  {
    $this->description = $description;
  }

  /**
   * @return StringPrimitive
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
   * @var StringPrimitive
   */
  protected $name;

  /**
   * @var StringPrimitive
   */
  protected $description;
}