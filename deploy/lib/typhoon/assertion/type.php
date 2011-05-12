<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Assertion;

use Typhoon;
use Typhoon\Assertion;
use Typhoon\Assertion\Exception\UnexpectedType;
use Typhoon\Primitive\String;
use Typhoon\Type as TypeObject;
use Typhoon\Type\Mixed;

class Type implements Assertion
{
  public function __construct()
  {
    $this->type = new Mixed;
  }

  public function assert()
  {
    if ($this->type->check($this->value)) return;

    throw new UnexpectedType(
      $this->value,
      new String((string)Typhoon::instance()->typeRenderer()->render($this->type))
    );
  }

  /**
   * @param TypeObject $type
   */
  public function setType(TypeObject $type)
  {
    $this->type = $type;
  }

  /**
   * @return TypeObject
   */
  public function type()
  {
    return $this->type;
  }

  /**
   * @param mixed $value
   */
  public function setValue($value)
  {
    $this->value = $value;
  }

  /**
   * @return mixed
   */
  public function value()
  {
    return $this->value;
  }

  /**
   * @var TypeObject
   */
  protected $type;

  /**
   * @var mixed
   */
  protected $value;
}