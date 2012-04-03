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

abstract class BadMethodCallException extends \BadMethodCallException implements Exception
{
  /**
   * @param String $message
   * @param \Exception $previous
   */
  public function __construct(String $message, \Exception $previous = null)
  {
    parent::__construct((string)$message, 0, $previous);
  }
}