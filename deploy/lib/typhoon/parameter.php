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

use Typhoon\Primitive\Boolean;
use Typhoon\Primitive\String;
use Typhoon\Type\Mixed as MixedType;

class Parameter
{
  public function __construct()
  {
    $this->type = new MixedType;
    $this->optional = new Boolean(false);
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
   * @param Boolean $type
   */
  public function setOptional(Boolean $optional)
  {
    $this->optional = $optional;
  }

  /**
   * @return Boolean
   */
  public function optional()
  {
    return $this->optional;
  }

  /**
   * @param String $name
   */
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

  /**
   * @param String $description
   */
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
   * @var Boolean
   */
  protected $optional;

  /**
   * @var String
   */
  protected $name;

  /**
   * @var String
   */
  protected $description;
}