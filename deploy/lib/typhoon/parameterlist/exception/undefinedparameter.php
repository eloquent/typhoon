<?php

namespace Typhoon\ParameterList\Exception;

class UndefinedParameter extends Exception
{
  public function __construct($index, \Exception $previous = null)
  {
    parent::__construct('No parameter defined for index '.$index.'.', $previous);
  }
}