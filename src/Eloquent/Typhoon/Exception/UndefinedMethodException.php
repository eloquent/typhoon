<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Exception;

use Eloquent\Typhoon\Primitive\String;

final class UndefinedMethodException extends BadMethodCallException
{
  /**
   * @param String $class
   * @param String $method
   * @param \Exception $previous
   */
  public function __construct(String $class, String $method, \Exception $previous = null)
  {
    $message = new String('Call to undefined method '.$class.'::'.$method.'().');

    parent::__construct($message, $previous);
  }
}
