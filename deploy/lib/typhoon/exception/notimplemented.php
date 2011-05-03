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