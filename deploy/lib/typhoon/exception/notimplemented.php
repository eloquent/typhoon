<?php

namespace Typhoon\Exception;

class NotImplemented extends Exception
{
  public function __construct($feature, \Exception $previous = null)
  {
    parent::__construct($feature.' is not implemented.', $previous);
  }
}