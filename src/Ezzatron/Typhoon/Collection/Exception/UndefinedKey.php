<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Collection\Exception;

use Exception as NativeException;
use Ezzatron\Typhoon\Primitive\String;

class UndefinedKey extends Exception
{
  /**
   * @param String $key
   * @param NativeException $previous
   */
  public function __construct(String $key, NativeException $previous = null)
  {
    $message = new String("Undefined key '".$key."'.");

    parent::__construct($message, $previous);
  }
}