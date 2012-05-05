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
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Type\Renderer\TypeRenderer;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Typhax\TyphaxTypeRenderer;

final class UnexpectedAttributeException extends LogicException implements UnexpectedInputException
{
  /**
   * @param mixed $value
   * @param String $attributeName
   * @param Type $expectedType
   * @param String $holderName
   * @param TypeInspector $typeInspector
   * @param TypeRenderer $typeRenderer
   * @param \Exception $previous
   */
  public function __construct(
    $value
    , String $attributeName
    , Type $expectedType = null
    , String $holderName = null
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
    $this->attributeName = $attributeName->value();
    $this->typeInspector = $typeInspector;
    $this->typeRenderer = $typeRenderer;

    $message =
      "Unexpected value of type '"
      .$this->typeRenderer->render($this->typeInspector->typeOf($this->value))
      ."' for attribute '"
      .$this->attributeName
      ."'"
    ;

    if ($holderName)
    {
      $this->holderName = $holderName->value();

      $message .=
        " of '"
        .$this->holderName
        ."'"
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
   * @return string
   */
  public function attributeName()
  {
    return $this->attributeName;
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
  public function holderName()
  {
    return $this->holderName;
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
   * @var string
   */
  protected $attributeName;

  /**
   * @var Type
   */
  protected $expectedType;

  /**
   * @var string
   */
  protected $holderName;

  /**
   * @var TypeInspector
   */
  protected $typeInspector;

  /**
   * @var TypeRenderer
   */
  protected $typeRenderer;
}
