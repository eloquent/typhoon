<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Exception;

use Exception as NativeException;
use Ezzatron\Typhoon\Primitive\String;

class NotImplemented extends Exception
{
  /**
   * @param String $feature
   * @param NativeException $previous
   */
  public function __construct(String $feature, NativeException $previous = null)
  {
    $message = new String($feature.' is not implemented.');

    parent::__construct($message, $previous);
  }
}