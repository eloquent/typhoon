<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\ParameterList\Exception;

use Exception as NativeException;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

class UndefinedParameter extends Exception
{
  /**
   * @param Integer $index
   * @param NativeException $previous
   */
  public function __construct(Integer $index, NativeException $previous = null)
  {
    $message = new String('No parameter defined for index '.$index.'.');

    parent::__construct($message, $previous);
  }
}