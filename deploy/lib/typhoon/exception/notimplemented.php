<?php

namespace Typhoon\Exception;

use Typhoon\Scalar\String;

class NotImplemented extends Exception
{
  public function __construct(String $feature, \Exception $previous = null)
  {
    parent::__construct($feature.' is not implemented.', $previous);
  }
}