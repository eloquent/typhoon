<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @codeCoverageIgnoreStart

namespace Eloquent\Typhoon\Type\Renderer;

use Eloquent\Typhoon\Type\Type;

interface TypeRenderer
{
  /**
   * @param Type $type
   *
   * @return string
   */
  public function render(Type $type);
}
