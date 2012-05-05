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
use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Type\Renderer\TypeRenderer;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Typhax\TyphaxTypeRenderer;

final class UnexpectedArgumentException extends LogicException implements UnexpectedInputException
{
  /**
   * @param mixed $value
   * @param Integer $index
   * @param Type $expectedType
   * @param String $parameterName
   * @param TypeInspector $typeInspector
   * @param TypeRenderer $typeRenderer
   * @param \Exception $previous
   */
  public function __construct(
    $value
    , Integer $index
    , Type $expectedType = null
    , String $parameterName = null
    , TypeInspector $typeInspector = null
    , TypeRenderer $typeRenderer = null
    , \Exception $previous = null
  )
  {
    if (null === $typeInspector)
    {
      $typeInspector = new TypeInspector;
    }
    if (null === $typeRenderer)
    {
      $typeRenderer = new TyphaxTypeRenderer;
    }

    $this->value = $value;
    $this->index = $index->value();
    $this->typeInspector = $typeInspector;
    $this->typeRenderer = $typeRenderer;

    $message =
      "Unexpected argument of type '"
      .$this->typeRenderer->render($this->typeInspector->typeOf($this->value))
      ."' at index "
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
    if ($expectedType)
    {
      $this->expectedType = $expectedType;

      $message .=
        " - expected '"
        .$this->typeRenderer->render($this->expectedType)
        ."'"
      ;
    }

    $message .= ".";

    parent::__construct(new String($message), $previous);
  }

  /**
   * @return mixed
   */
  public function value()
  {
    return $this->value;
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
   * @return TypeInspector
   */
  public function typeInspector()
  {
    return $this->typeInspector;
  }

  /**
   * @return TypeRenderer
   */
  public function typeRenderer()
  {
    return $this->typeRenderer;
  }

  /**
   * @var mixed
   */
  protected $value;

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
   * @var TypeInspector
   */
  protected $typeInspector;

  /**
   * @var TypeRenderer
   */
  protected $typeRenderer;
}
