<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\SubTyped;

use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Type\Type;

interface SubTypedType extends Type
{
  /**
   * @param array $subTypes
   */
  public function setTyphoonSubTypes(array $subTypes);
}
