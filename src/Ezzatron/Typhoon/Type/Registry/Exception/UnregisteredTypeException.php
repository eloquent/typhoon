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

use Ezzatron\Typhoon\Primitive\String;

class UnregisteredTypeException extends Exception
{
  /**
   * @param String $typeName
   * @param \Exception $previous
   */
  public function __construct(String $typeName, \Exception $previous = null)
  {
    $message = new String("No registered alias for type '".$typeName."'.");

    parent::__construct($message, $previous);
  }
}