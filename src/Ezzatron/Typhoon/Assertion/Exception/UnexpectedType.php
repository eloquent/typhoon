<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion\Exception;

use Exception as NativeException;
use Ezzatron\Typhoon\Primitive\String as StringPrimitive;

final class UnexpectedType extends Exception
{
  /**
   * @param mixed $value
   * @param String $expectedTypeName
   * @param NativeException $previous
   */
  public function __construct($value, StringPrimitive $expectedTypeName, NativeException $previous = null)
  {
    $message = new StringPrimitive("Unexpected type - expected '".$expectedTypeName."'.");
    
    parent::__construct($message, $previous);
  }
}