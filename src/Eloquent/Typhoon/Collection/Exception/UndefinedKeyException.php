<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Collection\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Exception\UndefinedKeyException as UndefinedKeyExceptionInterface;
use Eloquent\Typhoon\Primitive\String;

final class UndefinedKeyException extends LogicException implements UndefinedKeyExceptionInterface
{
  /**
   * @param String $key
   * @param \Exception $previous
   */
  public function __construct(String $key, \Exception $previous = null)
  {
    $message = new String("Undefined key '".$key."'.");

    parent::__construct($message, $previous);
  }
}
