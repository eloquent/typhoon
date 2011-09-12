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

class UnsupportedAttributeException extends Exception
{
  /**
   * @param String $typeName
   * @param String $attribute
   * @param \Exception $previous
   */
  public function __construct(String $className, String $attribute, \Exception $previous = null)
  {
    $message = new String("The attribute '".$attribute."' is not supported by class '".$className."'.");

    parent::__construct($message, $previous);
  }
}