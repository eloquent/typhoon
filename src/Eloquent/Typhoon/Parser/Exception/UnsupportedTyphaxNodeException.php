<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parser\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Primitive\String;

final class UnsupportedTyphaxNodeException extends LogicException
{
  /**
   * @param String $class
   * @param \Exception $previous
   */
  public function __construct(String $class, \Exception $previous = null)
  {
    $message = new String("Unsupported Typhax node of class '".$class."' encountered.");

    parent::__construct($message, $previous);
  }
}
