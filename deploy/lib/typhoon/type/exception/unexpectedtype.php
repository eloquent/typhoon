<?php

namespace Typhoon\Type\Exception;

use Typhoon\Primitive\String;
use Typhoon\Type;

final class UnexpectedType extends Exception
{
  public function __construct($value, Type $expectedType, \Exception $previous = null)
  {
    $this->expectedType = $expectedType;

    $message = new String("Unexpected type - expected '".$this->expectedType."'.");
    
    parent::__construct($message, $previous);
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