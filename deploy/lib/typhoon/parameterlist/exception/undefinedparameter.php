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
use Typhoon\Primitive\Integer as IntegerPrimitive;
use Typhoon\Primitive\String as StringPrimitive;

class UndefinedParameter extends Exception
{
  /**
   * @param IntegerPrimitive $index
   * @param NativeException $previous
   */
  public function __construct(IntegerPrimitive $index, NativeException $previous = null)
  {
    $message = new StringPrimitive('No parameter defined for index '.$index.'.');

    parent::__construct($message, $previous);
  }
}