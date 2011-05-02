<?php

namespace Typhoon\Exception;

use Exception as NativeException;
use Typhoon\Primitive\String as StringPrimitive;

abstract class Exception extends NativeException
{
  /**
   * @param StringPrimitive $message
   * @param NativeException $previous
   */
  public function __construct(StringPrimitive $message, NativeException $previous = null)
  {
    parent::__construct((string)$message, 0, $previous);
  }
}