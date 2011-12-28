<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute\Exception;

use Ezzatron\Typhoon\Exception\LogicException;
use Ezzatron\Typhoon\Primitive\String;

final class FinalizedException extends LogicException
{
  /**
   * @param String $key
   * @param \Exception $previous
   */
  public function __construct(String $key, \Exception $previous = null)
  {
    $message = new String("Unable to modify key '".$key."'. Attributes have been finalized.");

    parent::__construct($message, $previous);
  }
}