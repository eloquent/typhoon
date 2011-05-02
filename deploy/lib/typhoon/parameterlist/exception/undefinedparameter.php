<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Scalar\Integer;
use Typhoon\Scalar\String;

class UndefinedParameter extends Exception
{
  public function __construct(Integer $index, \Exception $previous = null)
  {
    $message = new String('No parameter defined for index '.$index.'.');

    parent::__construct($message, $previous);
  }
}