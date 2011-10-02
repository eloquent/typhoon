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

use Ezzatron\Typhoon\Typhoon;
use Ezzatron\Typhoon\Parameter\Parameter;
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

class UnexpectedAttributeException extends Exception
{
  /**
   * @param String $typeName
   * @param String $attributeName
   * @param String $expectedTypeName
   * @param String $holderName
   * @param \Exception $previous
   */
  public function __construct(String $typeName, String $attributeName, String $expectedTypeName = null, String $holderName = null, \Exception $previous = null)
  {
    $this->typeName = $typeName->value();
    $this->attributeName = $attributeName->value();

    $message = "Unexpected value of type '".$this->typeName."' for attribute '".$this->attributeName."'";

    if ($holderName)
    {
      $this->holderName = $holderName->value();

      $message .= " of '".$this->holderName."'";
    }
    if ($expectedTypeName)
    {
      $this->expectedTypeName = $expectedTypeName->value();

      $message .= " - expected '".$this->expectedTypeName."'";
    }

    $message .= ".";

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
  protected $typeName;

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