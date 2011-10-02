<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Primitive;

use Ezzatron\Typhoon\Assertion\Exception\UnexpectedArgumentException;
use Ezzatron\Typhoon\Assertion\Exception\UnexpectedTypeException;
use Ezzatron\Typhoon\Assertion\TypeAssertion;
use Ezzatron\Typhoon\Typhoon;

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
        new String($e->typeName())
        , new Integer(0)
        , new String($e->expectedTypeName())
        , new String('value')
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