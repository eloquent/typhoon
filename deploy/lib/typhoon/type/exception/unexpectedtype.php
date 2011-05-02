<?php

namespace Typhoon\Type\Exception;

use Typhoon\Type;

final class UnexpectedType extends Exception
{
  public function __construct($value, Type $expectedType, \Exception $previous = null)
  {
    $this->expectedType = $expectedType;
    
    parent::__construct("Unexpected type - expected '".$this->expectedType."'.", $previous);
  }

  /**
   * @return Type
   */
  public function expectedType()
  {
    return $this->expectedType;
  }

  /**
   * @var Type
   */
  protected $expectedType;
}