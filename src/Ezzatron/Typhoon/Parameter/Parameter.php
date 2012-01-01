<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Parameter;

use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\Type;

class Parameter
{
  public function __construct()
  {
    $this->type = new MixedType;
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
   * @param Boolean $optional
   */
  public function setOptional(Boolean $optional)
  {
    $this->optional = $optional->value();
  }

  /**
   * @return boolean
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
    $this->name = $name->value();
  }

  /**
   * @return string
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
    $this->description = $description->value();
  }

  /**
   * @return string
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
   * @var boolean
   */
  protected $optional = false;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $description;
}
