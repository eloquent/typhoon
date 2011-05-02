<?php

namespace Typhoon\Exception;

use Typhoon\Primitive\String;

class NotImplemented extends Exception
{
  public function __construct(String $feature, \Exception $previous = null)
  {
    $message = new String($feature.' is not implemented.');

    parent::__construct($message, $previous);
  }
}