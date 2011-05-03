<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Typhoon\Primitive\Boolean as BooleanPrimitive;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type\Mixed as MixedType;

class Parameter
{
  public function __construct()
  {
    $this->type = new MixedType;
    $this->optional = new BooleanPrimitive(false);
  }

  /**
   * @param Type $type
   */
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
   * @param BooleanPrimitive $type
   */
  public function setOptional(BooleanPrimitive $optional)
  {
    $this->optional = $optional;
  }

  /**
   * @return BooleanPrimitive
   */
  public function optional()
  {
    return $this->optional;
  }

  /**
   * @param StringPrimitive $name
   */
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

  /**
   * @param StringPrimitive $description
   */
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
   * @var BooleanPrimitive
   */
  protected $optional;

  /**
   * @var StringPrimitive
   */
  protected $name;

  /**
   * @var StringPrimitive
   */
  protected $description;
}