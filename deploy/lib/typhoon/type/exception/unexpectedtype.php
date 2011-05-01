<?php

namespace Typhoon\Type\Exception;

final class UnexpectedType extends Exception
{
  public function __construct(\Typhoon\Type $expectedType, \Exception $previous = null)
  {
    $this->expectedType = $expectedType;
    
    parent::__construct("Unexpected type - expected '".$this->expectedType."'.", $previous);
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