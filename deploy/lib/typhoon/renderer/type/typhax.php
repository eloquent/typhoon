<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Renderer\Type;

use Typhoon\Renderer\Type as TypeRenderer;
use Typhoon\Type as Type;

class Typhax extends TypeRenderer
{
  /**
   * @param Type $type
   *
   * @return string
   */
  public function render(Type $type)
  {
    return
      $this->renderAlias($type)
      .$this->renderArguments($type)
    ;
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  protected function renderAlias(Type $type)
  {
    return $this->typeRegistry()->alias($type);
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  protected function renderArguments(Type $type)
  {
    if (!$type->arguments()) return '';

    $rendered = '(';

    foreach ($type->arguments() as $index => $argument)
    {
      if ($index > 0) $rendered .= ', ';

      $rendered .= $this->renderArgument($argument);
    }

    $rendered .= ')';

    return $rendered;
  }

  /**
   * @param mixed $argument
   *
   * @return string
   */
  protected function renderArgument($argument)
  {
    return var_export($argument, true);
  }
}