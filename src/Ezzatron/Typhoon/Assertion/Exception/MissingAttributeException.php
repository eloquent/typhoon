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

final class MissingAttributeException extends LogicException implements UnexpectedInputException
{
  /**
   * @param String $attributeName
   * @param String $expectedTypeName
   * @param String $holderName
   * @param \Exception $previous
   */
  public function __construct(String $attributeName, String $expectedTypeName, String $holderName = null, \Exception $previous = null)
  {
    $this->attributeName = $attributeName->value();
    $this->expectedTypeName = $expectedTypeName->value();

    $message = "Missing required attribute '".$this->attributeName."'";

    if ($holderName)
    {
      $this->holderName = $holderName->value();
      
      $message .= " for '".$this->holderName."'";
    }

    $message .= " - expected '".$this->expectedTypeName."'.";

    parent::__construct(new String($message), $previous);
  }

  /**
   * @return string
   */
  public function attributeName()
  {
    return $this->attributeName;
  }

  /**
   * @return string
   */
  public function expectedTypeName()
  {
    return $this->expectedTypeName;
  }

  /**
   * @return string
   */
  public function holderName()
  {
    return $this->holderName;
  }

  /**
   * @var string
   */
  protected $attributeName;

  /**
   * @var string
   */
  protected $expectedTypeName;

  /**
   * @var string
   */
  protected $holderName;
}
