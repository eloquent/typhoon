<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Assertion;

use Eloquent\Typhoon\Typhoon;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Type\MixedType;

class TypeAssertion implements Assertion
{
  public function __construct()
  {
    $this->type = new MixedType;
  }

  public function assert()
  {
    if ($this->type->typhoonCheck($this->value))
    {
      return;
    }

    throw new Exception\UnexpectedTypeException(
      Typhoon::instance()->typeInspector()->typeOf($this->value)
      , $this->type
    );
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
   * @var Type
   */
  protected $type;

  /**
   * @var mixed
   */
  protected $value;
}
