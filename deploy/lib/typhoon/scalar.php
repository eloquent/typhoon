<?php

namespace Typhoon;

abstract class Scalar
{
  final public function __construct($value)
  {
    $this->type()->assert($value);

    $this->value = $value;
  }

  /**
   * @return string
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
   * @return \Typhoon\Type
   */
  abstract public function type();

  /**
   * @var mixed
   */
  private $value;
}