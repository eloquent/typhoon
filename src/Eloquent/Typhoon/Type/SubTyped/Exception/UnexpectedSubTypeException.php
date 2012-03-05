<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\SubTyped\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;

final class UnexpectedSubTypeException extends LogicException
{
  /**
   * @param String $class
   * @param Integer $position
   * @param \Exception $previous
   */
  public function __construct(String $class, Integer $position, \Exception $previous = null)
  {
    $message = new String("Unexpected subtype at position ".$position." in type class '".$class."'.");

    parent::__construct($message, $previous);
  }
}
