<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type\Exception;

use Exception as NativeException;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type;

final class UnexpectedType extends Exception
{
  /**
   * @param mixed $value
   * @param Type $expectedType
   * @param NativeException $previous
   */
  public function __construct($value, Type $expectedType, NativeException $previous = null)
  {
    $this->expectedType = $expectedType;

    $message = new StringPrimitive("Unexpected type - expected '".$this->expectedType."'.");
    
    parent::__construct($message, $previous);
  }

  /**
   * @return Type
   */
  public function expectedType()
  {
    return $this->expectedType;
  }

  /**
   * @var Type
   */
  protected $expectedType;
}