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
use Eloquent\Typhoon\Exception\UndefinedKeyException;
use Eloquent\Typhoon\Primitive\String;

final class UnregisteredTypeAliasException extends LogicException implements UndefinedKeyException
{
  /**
   * @param String $alias
   * @param \Exception $previous
   */
  public function __construct(String $alias, \Exception $previous = null)
  {
    $message = new String("No type registered for alias '".$alias."'.");

    parent::__construct($message, $previous);
  }
}
