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

final class MissingArgumentException extends LogicException implements UnexpectedInputException
{
  /**
   * @param Integer $index
   * @param String $expectedTypeName
   * @param String $parameterName
   * @param \Exception $previous
   */
  public function __construct(Integer $index, String $expectedTypeName, String $parameterName = null, \Exception $previous = null)
  {
    $this->index = $index->value();
    $this->expectedTypeName = $expectedTypeName->value();

    $message = "Missing argument at index ".$this->index;

    if ($parameterName)
    {
      $this->parameterName = $parameterName->value();

      $message .= " (".$this->parameterName.")";
    }

    $message .= " - expected '".$this->expectedTypeName."'.";

    parent::__construct(new String($message), $previous);
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
