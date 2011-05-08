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

use Typhoon;
use Typhoon\Assertion\Exception\UnexpectedArgument;
use Typhoon\Assertion\Exception\UnexpectedType;
use Typhoon\Assertion\Type as TypeAssertion;
use Typhoon\Primitive\Integer;

abstract class Primitive
{
  /**
   * @param mixed $value
   */
  final public function __construct($value)
  {
    $assertion = $this->typeAssertion();
    $assertion->setType($this->type());
    $assertion->setValue($value);
    
    try
    {
      $assertion->assert();
    }
    catch (UnexpectedType $e)
    {
      $parameter = new Parameter;
      $parameter->setType($this->type());

      throw new UnexpectedArgument($value, new Integer(0), $parameter, $e);
    }

    $this->value = $value;
  }

  /**
   * @return mixed
   */
  final public function value()
  {
    return $this->value;
  }

  /**
   * @return string
   */
  final public function __toString()
  {
    return (string)$this->value;
  }

  /**
   * @return TypeAssertion
   */
  public function typeAssertion()
  {
    return Typhoon::instance()->typeAssertion();
  }

  /**
   * @return Type
   */
  abstract public function type();

  /**
   * @var mixed
   */
  private $value;
}