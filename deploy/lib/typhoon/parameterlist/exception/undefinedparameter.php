<?php

namespace Typhoon\ParameterList\Exception;

use Exception as NativeException;
use Typhoon\Primitive\Integer as IntegerPrimitive;
use Typhoon\Primitive\String as StringPrimitive;

class UndefinedParameter extends Exception
{
  /**
   * @param IntegerPrimitive $index
   * @param NativeException $previous
   */
  public function __construct(IntegerPrimitive $index, NativeException $previous = null)
  {
    $message = new StringPrimitive('No parameter defined for index '.$index.'.');

    parent::__construct($message, $previous);
  }
}