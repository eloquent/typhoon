<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;

class UndefinedParameter extends Exception
{
  public function __construct(Integer $index, \Exception $previous = null)
  {
    $message = new String('No parameter defined for index '.$index.'.');

    parent::__construct($message, $previous);
  }
}