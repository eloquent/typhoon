<?php

namespace Typhoon\Exception;

use Exception as NativeException;
use Typhoon\Primitive\String as StringPrimitive;

class NotImplemented extends Exception
{
  /**
   * @param StringPrimitive $feature
   * @param NativeException $previous
   */
  public function __construct(StringPrimitive $feature, NativeException $previous = null)
  {
    $message = new StringPrimitive($feature.' is not implemented.');

    parent::__construct($message, $previous);
  }
}