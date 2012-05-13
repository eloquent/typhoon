<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter\ParameterList\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Primitive\String;

final class VariableLengthParameterNotLastException extends LogicException
{
  /**
   * @param String $parameterName
   * @param \Exception $previous
   */
  public function __construct(String $parameterName, \Exception $previous = null)
  {
    $message = new String("Parameter '".$parameterName."' is marked as variable length, but is not the last parameter.");

    parent::__construct($message, $previous);
  }
}
