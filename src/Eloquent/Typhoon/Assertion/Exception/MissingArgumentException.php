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
use Eloquent\Typhoon\Exception\UnexpectedInputException;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Primitive\String;

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
