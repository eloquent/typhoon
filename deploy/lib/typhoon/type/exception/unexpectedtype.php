<?php

namespace Typhoon\Type\Exception;

use Exception as NativeException;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type;

final class UnexpectedType extends Exception
{
  /**
   * @param mixed $value
   * @param Type $expectedType
   * @param NativeException $previous
   */
  public function __construct($value, Type $expectedType, NativeException $previous = null)
  {
    $this->expectedType = $expectedType;

    $message = new StringPrimitive("Unexpected type - expected '".$this->expectedType."'.");
    
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