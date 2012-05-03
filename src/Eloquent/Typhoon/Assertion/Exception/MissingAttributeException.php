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
use Eloquent\Typhoon\Type\Renderer\TypeRenderer;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Typhax\TyphaxTypeRenderer;

final class MissingAttributeException extends LogicException implements UnexpectedInputException
{
  /**
   * @param String $attributeName
   * @param Type $expectedType
   * @param String $holderName
   * @param TypeRenderer $typeRenderer
   * @param \Exception $previous
   */
  public function __construct(String $attributeName, Type $expectedType, String $holderName = null, TypeRenderer $typeRenderer = null, \Exception $previous = null)
  {
    if (null === $typeRenderer)
    {
      $typeRenderer = new TyphaxTypeRenderer;
    }

    $this->attributeName = $attributeName->value();
    $this->expectedType = $expectedType;
    $this->typeRenderer = $typeRenderer;

    $message =
      "Missing required attribute '"
      .$this->attributeName
      ."'"
    ;

    if ($holderName)
    {
      $this->holderName = $holderName->value();

      $message .=
        " for '"
        .$this->holderName
        ."'"
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
   * @return TypeRenderer
   */
  public function typeRenderer()
  {
    return $this->typeRenderer;
  }

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
   * @var TypeRenderer
   */
  protected $typeRenderer;
}
