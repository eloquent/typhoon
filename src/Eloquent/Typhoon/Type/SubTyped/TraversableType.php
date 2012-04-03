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

use Eloquent\Typhoon\Type\Type;

interface TraversableType extends SubTypedType
{
  /**
   * @param Type $subType
   */
  public function setTyphoonSubType(Type $subType);

  /**
   * @return Type
   */
  public function typhoonSubType();

  /**
   * @param Type $keyType
   */
  public function setTyphoonKeyType(Type $keyType);

  /**
   * @return Type
   */
  public function typhoonKeyType();
}