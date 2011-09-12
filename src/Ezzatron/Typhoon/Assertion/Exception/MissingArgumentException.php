<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion\Exception;

use Ezzatron\Typhoon\Typhoon;
use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

class MissingArgumentException extends Exception
{
  /**
   * @param Integer $index
   * @param Parameter $parameter
   * @param \Exception $previous
   */
  public function __construct(Integer $index, Parameter $parameter, \Exception $previous = null)
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