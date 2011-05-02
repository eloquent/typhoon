<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Parameter;
use Typhoon\Scalar\Integer;

class UnexpectedArgument extends Exception
{
  public function __construct($value, Integer $index, Parameter $parameter, \Exception $previous = null)
  {
    parent::__construct("Unexpected argument at index ".$index." - expected '".$parameter->type()."'.", $previous);
  }
}