<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhoon\Primitive\Boolean;

class InterfaceNameType extends BaseClassNameType
{
  public function __construct(array $attributes = null)
  {
    parent::__construct(new Boolean(true), $attributes);
  }
}
