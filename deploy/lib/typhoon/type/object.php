<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Type;

class Object extends Type
{
  /**
   * @param string $class
   */
  public function construct($class = null)
  {
    if (null !== $class) new StringPrimitive($class);
    
    $this->class = $class;
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
  {
    if ($this->class)
    {
      return $value instanceof $this->class;
    }

    return is_object($value);
  }

  /**
   * @var string
   */
  protected $class;
}