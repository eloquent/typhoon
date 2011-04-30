<?php

namespace Typhoon\Type\Exception;

final class UnexpectedType extends Exception
{
  public function __construct(\Exception $previous = null)
  {
    $this->message = 'Unexpected type.';
    
    parent::__construct($previous);
  }
}