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
use Eloquent\Typhoon\Exception\UndefinedIndexException;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;

final class UndefinedParameterException extends LogicException implements UndefinedIndexException
{
  /**
   * @param Integer $index
   * @param \Exception $previous
   */
  public function __construct(Integer $index, \Exception $previous = null)
  {
    $message = new String('No parameter defined for index '.$index.'.');

    parent::__construct($message, $previous);
  }
}
