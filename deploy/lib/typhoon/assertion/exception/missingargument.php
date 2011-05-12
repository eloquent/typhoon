<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Assertion\Exception;

use Exception as NativeException;
use Typhoon;
use Typhoon\Parameter;
use Typhoon\Primitive\Integer;
use Typhoon\Primitive\String;

class MissingArgument extends Exception
{
  /**
   * @param Integer $index
   * @param Parameter $parameter
   * @param NativeException $previous
   */
  public function __construct(Integer $index, Parameter $parameter, NativeException $previous = null)
  {
    $message =
      "Missing argument at index "
      .$index
    ;

    if ($parameter->name())
    {
      $message .=
        " ("
        .$parameter->name()
        .")"
      ;
    }

    $message .=
      " - expected '"
      .Typhoon::instance()->typeRenderer()->render($parameter->type())
      ."'."
    ;

    parent::__construct(new String($message), $previous);
  }
}