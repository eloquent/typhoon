<?php

namespace Typhoon\Exception;

use Typhoon\Primitive\String;

abstract class Exception extends \Exception
{
  public function __construct(String $message, \Exception $previous = null)
  {
    parent::__construct((string)$message, 0, $previous);
  }
}