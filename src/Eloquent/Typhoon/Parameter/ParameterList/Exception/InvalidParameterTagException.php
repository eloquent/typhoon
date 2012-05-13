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

final class InvalidParameterTagException extends LogicException
{
  /**
   * @param String $tagContent
   * @param \Exception $previous
   */
  public function __construct(String $tagContent, \Exception $previous = null)
  {
    $message = new String("Invalid documentation block parameter specification '".$tagContent."'.");

    parent::__construct($message, $previous);
  }
}
