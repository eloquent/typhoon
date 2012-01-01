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
use Ezzatron\Typhoon\Primitive\Integer;
use Ezzatron\Typhoon\Primitive\String;

final class UnexpectedArgumentException extends LogicException implements UnexpectedInputException
{
  /**
   * @param String $typeName
   * @param Integer $index
   * @param String $expectedTypeName
   * @param String $parameterName
   * @param \Exception $previous
   */
  public function __construct(String $typeName, Integer $index, String $expectedTypeName = null, String $parameterName = null, \Exception $previous = null)
  {
    $this->typeName = $typeName->value();
    $this->index = $index->value();

    $message = "Unexpected argument of type '".$this->typeName."' at index ".$this->index;

    if ($parameterName)
    {
      $this->parameterName = $parameterName->value();

      $message .= " (".$this->parameterName.")";
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
   * @return integer
   */
  public function index()
  {
    return $this->index;
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
  public function parameterName()
  {
    return $this->parameterName;
  }

  /**
   * @var string
   */
  protected $typeName;

  /**
   * @var integer
   */
  protected $index;

  /**
   * @var string
   */
  protected $expectedTypeName;

  /**
   * @var string
   */
  protected $parameterName;
}
