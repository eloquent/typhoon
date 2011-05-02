<?php

namespace Typhoon\Exception;

use Typhoon\Scalar\String;

abstract class Exception extends \Exception
{
  public function __construct(String $message, \Exception $previous = null)
  {
    parent::__construct((string)$message, 0, $previous);
  }
}