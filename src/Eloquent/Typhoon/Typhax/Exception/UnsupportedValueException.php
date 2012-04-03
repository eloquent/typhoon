<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Typhax\Exception;

use Eloquent\Typhoon\Exception\LogicException;
use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Typhax\TyphaxTypeRenderer;

final class UnsupportedValueException extends LogicException
{
  /**
   * @param mixed $value
   * @param TypeInspector $typeInspector
   * @param TypeRenderer $typeRenderer
   * @param \Exception $previous
   */
  public function __construct(
    $value
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

    $message = new String(
      "Cannot render value of unsupported type '"
      .$typeRenderer->render($typeInspector->typeOf($value))
      ."'."
    );

    parent::__construct($message, $previous);
  }
}
