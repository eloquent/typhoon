<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute\Exception;

use Ezzatron\Typhoon\Primitive\String;

class RequiredAttributeException extends Exception
{
  /**
   * @param String $attribute
   * @param String $holder
   * @param \Exception $previous
   */
  public function __construct(String $attribute, String $holder = null, \Exception $previous = null)
  {
    $message = "The attribute '".$attribute."' is required";
    if (null === $holder)
    {
      $message .= '.';
    }
    else
    {
      $message .= " by '".$holder."'.";
    }

    parent::__construct(new String($message), $previous);
  }
}