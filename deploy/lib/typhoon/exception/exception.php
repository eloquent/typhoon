<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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