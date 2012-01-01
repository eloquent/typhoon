<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion;

use Ezzatron\Typhoon\Typhoon;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\Type;
use Ezzatron\Typhoon\Type\MixedType;

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

    $typeName = Typhoon::instance()->typeRenderer()->render(
      Typhoon::instance()->typeInspector()->typeOf($this->value)
    );
    $expectedTypeName = Typhoon::instance()->typeRenderer()->render(
      $this->type
    );

    throw new Exception\UnexpectedTypeException(
      new String((string)$typeName),
      new String((string)$expectedTypeName)
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
