<?php

namespace Typhoon\ParameterList\Exception;

use Typhoon\Parameter;
use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;

class UnexpectedArgument extends Exception
{
  public function __construct($value, Integer $index, Parameter $parameter, \Exception $previous = null)
  {
    $message =
      "Unexpected argument at index "
      .$index
    ;

    if ($parameter->name()) $message .=
      " ("
      .$parameter->name()
      .")"
     ;

    $message .=
      " - expected '"
      .$parameter->type()
      ."'."
    ;

    parent::__construct(new String($message), $previous);
  }
}