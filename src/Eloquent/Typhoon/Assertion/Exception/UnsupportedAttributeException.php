<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Assertion\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Exception\UndefinedKeyException;
use Eloquent\Typhoon\Exception\UnexpectedInputException;
use Eloquent\Typhoon\Primitive\String;

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
