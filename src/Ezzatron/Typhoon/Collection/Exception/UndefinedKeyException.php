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

use Ezzatron\Typhoon\Primitive\String;

class UndefinedKeyException extends Exception
{
  /**
   * @param String $key
   * @param \Exception $previous
   */
  public function __construct(String $key, \Exception $previous = null)
  {
    $message = new String("Undefined key '".$key."'.");

    parent::__construct($message, $previous);
  }
}