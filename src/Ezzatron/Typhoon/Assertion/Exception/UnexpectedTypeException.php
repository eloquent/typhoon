<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Assertion\Exception;

use Ezzatron\Typhoon\Exception\LogicException;
use Ezzatron\Typhoon\Exception\UnexpectedInputException;
use Ezzatron\Typhoon\Primitive\String;

final class UnexpectedTypeException extends LogicException implements UnexpectedInputException
{
  /**
   * @param String $typeName
   * @param String $expectedTypeName
   * @param \Exception $previous
   */
  public function __construct(String $typeName, String $expectedTypeName, \Exception $previous = null)
  {
    $this->typeName = $typeName->value();
    $this->expectedTypeName = $expectedTypeName->value();

    $message = "Unexpected value of type '".$this->typeName."' - expected '".$this->expectedTypeName."'.";

    parent::__construct(new String($message), $previous);
  }
  
  /**
   * @return string
   */
  public function typeName()
  {
    return $this->typeName;
  }
  
  /**
   * @return string
   */
  public function expectedTypeName()
  {
    return $this->expectedTypeName;
  }

  /**
   * @var string
   */
  protected $typeName;

  /**
   * @var string
   */
  protected $expectedTypeName;
}
