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
use Ezzatron\Typhoon\Exception\UndefinedKeyException;
use Ezzatron\Typhoon\Exception\UnexpectedInputException;
use Ezzatron\Typhoon\Primitive\String;

final class UnsupportedAttributeException extends LogicException implements UndefinedKeyException, UnexpectedInputException
{
  /**
   * @param String $attributeName
   * @param String $holderName
   * @param \Exception $previous
   */
  public function __construct(String $attributeName, String $holderName = null, \Exception $previous = null)
  {
    $this->attributeName = $attributeName->value();

    $message = "Attribute '".$this->attributeName."' is not supported";

    if ($holderName)
    {
      $this->holderName = $holderName->value();

      $message .= " by '".$this->holderName."'";
    }

    $message .= ".";

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
  protected $holderName;
}