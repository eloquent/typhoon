<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Primitive;

use Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException;
use Eloquent\Typhoon\Assertion\Exception\UnexpectedTypeException;
use Eloquent\Typhoon\Assertion\TypeAssertion;

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
    catch (UnexpectedTypeException $e)
    {
      throw new UnexpectedArgumentException(
        $e->type()
        , new Integer(0)
        , $e->expectedType()
        , new String('value')
        , $e->typeRenderer()
        , $e
      );
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
  protected function typeAssertion()
  {
    return new TypeAssertion;
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
