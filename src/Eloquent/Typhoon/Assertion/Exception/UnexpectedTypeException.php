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

final class UnexpectedTypeException extends LogicException implements UnexpectedInputException
{
  /**
   * @param Type $type
   * @param Type $expectedType
   * @param TypeRenderer $typeRenderer
   * @param \Exception $previous
   */
  public function __construct(Type $type, Type $expectedType, TypeRenderer $typeRenderer = null, \Exception $previous = null)
  {
    if (null === $typeRenderer)
    {
      $typeRenderer = new TyphaxTypeRenderer;
    }

    $this->type = $type;
    $this->expectedType = $expectedType;
    $this->typeRenderer = $typeRenderer;

    $message =
      "Unexpected value of type '"
      .$this->typeRenderer->render($this->type)
      ."' - expected '"
      .$this->typeRenderer->render($this->expectedType)
      ."'."
    ;

    parent::__construct(new String($message), $previous);
  }

  /**
   * @return Type
   */
  public function type()
  {
    return $this->type;
  }

  /**
   * @return Type
   */
  public function expectedType()
  {
    return $this->expectedType;
  }

  /**
   * @return TypeRenderer
   */
  public function typeRenderer()
  {
    return $this->typeRenderer;
  }

  /**
   * @var Type
   */
  protected $type;

  /**
   * @var Type
   */
  protected $expectedType;

  /**
   * @var TypeRenderer
   */
  protected $typeRenderer;
}
