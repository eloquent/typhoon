<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Composite;

use Ezzatron\Typhoon\Type\BaseType;
use Ezzatron\Typhoon\Type\Type;

abstract class BaseCompositeType extends BaseType implements CompositeType
{
  /**
   * @param Type $type
   */
  public function addTyphoonType(Type $type)
  {
    $this->types[] = $type;
  }

  /**
   * @var array
   */
  protected $types = array();
}
