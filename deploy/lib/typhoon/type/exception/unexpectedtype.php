<?php

namespace Typhoon\Type\Exception;

final class UnexpectedType extends Exception
{
  public function __construct(\Typhoon\Type $expectedType, \Exception $previous = null)
  {
    $this->expectedType = $expectedType;
    $this->message = "Unexpected type - expected '".$this->expectedType."'.";
    
    parent::__construct($previous);
  }

  /**
   * @return \Typhoon\Type
   */
  public function expectedType()
  {
    return $this->expectedType;
  }

  /**
   * @var \Typhoon\Type
   */
  protected $expectedType;
}