<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\TypeRegistry\Exception;

use Exception as NativeException;
use Typhoon\Primitive\String;
use Typhoon\Type;

class UnregisteredType extends Exception
{
  /**
   * @param Type $type
   * @param NativeException $previous
   */
  public function __construct(Type $type, NativeException $previous = null)
  {
    $message = new String("No registered alias for type '".get_class($type)."'.");

    parent::__construct($message, $previous);
  }
}