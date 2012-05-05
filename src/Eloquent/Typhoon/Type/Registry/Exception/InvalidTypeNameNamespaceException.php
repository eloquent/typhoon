<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Registry\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Primitive\String;

final class InvalidTypeNameNamespaceException extends LogicException
{
  /**
   * @param String $typeName
   * @param \Exception $previous
   */
  public function __construct(String $typeName, \Exception $previous = null)
  {
    $message = new String("Type name '".$typeName."' does not contain a valid namespace.");

    parent::__construct($message, $previous);
  }
}
