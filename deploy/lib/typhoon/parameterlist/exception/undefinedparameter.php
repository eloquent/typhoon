<?php

namespace Typhoon\ParameterList\Exception;

use \Typhoon\Scalar\Integer;

class UndefinedParameter extends Exception
{
  public function __construct(Integer $index, \Exception $previous = null)
  {
    parent::__construct('No parameter defined for index '.$index.'.', $previous);
  }
}