<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Registry\Exception;

use Ezzatron\Typhoon\Exception\LogicException;
use Ezzatron\Typhoon\Exception\UndefinedKeyException;
use Ezzatron\Typhoon\Primitive\String;

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