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

use Ezzatron\Typhoon\Primitive\String;

final class NotImplementedException extends LogicException
{
  /**
   * @param String $feature
   * @param \Exception $previous
   */
  public function __construct(String $feature, \Exception $previous = null)
  {
    $message = new String($feature.' is not implemented.');

    parent::__construct($message, $previous);
  }
}
