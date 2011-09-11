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

class UnexpectedArgument extends Exception
{
  /**
   * @param mixed $value
   * @param Integer $index
   * @param Parameter $parameter
   * @param NativeException $previous
   */
  public function __construct($value, Integer $index, Parameter $parameter = null, NativeException $previous = null)
  {
    $message =
      "Unexpected argument at index "
      .$index
    ;

    if ($parameter)
    {
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
        ."'"
      ;
    }

    $message .= ".";

    parent::__construct(new String($message), $previous);
  }
}