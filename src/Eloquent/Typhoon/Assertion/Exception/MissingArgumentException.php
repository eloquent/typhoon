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
use Eloquent\Typhoon\Type\Renderer\TypeRenderer;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Typhax\TyphaxTypeRenderer;

final class MissingArgumentException extends LogicException implements UnexpectedInputException
{
  /**
   * @param Integer $index
   * @param Type $expectedType
   * @param String $parameterName
   * @param TypeRenderer $typeRenderer
   * @param \Exception $previous
   */
  public function __construct(Integer $index, Type $expectedType, String $parameterName = null, TypeRenderer $typeRenderer = null, \Exception $previous = null)
  {
    if (null === $typeRenderer)
    {
      $typeRenderer = new TyphaxTypeRenderer;
    }

    $this->index = $index->value();
    $this->expectedType = $expectedType;
    $this->typeRenderer = $typeRenderer;

    $message =
      "Missing argument at index "
      .$this->index
    ;

    if ($parameterName)
    {
      $this->parameterName = $parameterName->value();

      $message .=
        " ("
        .$this->parameterName
        .")"
      ;
    }

    $message .=
      " - expected '"
      .$this->typeRenderer->render($this->expectedType)
      ."'."
    ;

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
   * @return Type
   */
  public function expectedType()
  {
    return $this->expectedType;
  }

  /**
   * @return string
   */
  public function parameterName()
  {
    return $this->parameterName;
  }

  /**
   * @return TypeRenderer
   */
  public function typeRenderer()
  {
    return $this->typeRenderer;
  }

  /**
   * @var integer
   */
  protected $index;

  /**
   * @var Type
   */
  protected $expectedType;

  /**
   * @var string
   */
  protected $parameterName;

  /**
   * @var TypeRenderer
   */
  protected $typeRenderer;
}
