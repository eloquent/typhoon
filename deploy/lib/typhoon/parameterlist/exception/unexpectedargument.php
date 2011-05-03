<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\ParameterList\Exception;

use Exception as NativeException;
use Typhoon\Parameter;
use Typhoon\Primitive\Integer as IntegerPrimitive;
use Typhoon\Primitive\String as StringPrimitive;

class UnexpectedArgument extends Exception
{
  /**
   * @param mixed $value
   * @param IntegerPrimitive $index
   * @param Parameter $parameter
   * @param NativeException $previous
   */
  public function __construct($value, IntegerPrimitive $index, Parameter $parameter, NativeException $previous = null)
  {
    $message =
      "Unexpected argument at index "
      .$index
    ;

    if ($parameter->name()) $message .=
      " ("
      .$parameter->name()
      .")"
     ;

    $message .=
      " - expected '"
      .$parameter->type()
      ."'."
    ;

    parent::__construct(new StringPrimitive($message), $previous);
  }
}