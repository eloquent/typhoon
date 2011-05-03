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
   * @param StringPrimitive $class
   */
  public function __construct(StringPrimitive $class = null)
  {
    if (null !== $class) $this->class = $class->value();
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
   * @return string
   */
  public function __toString()
  {
    $string = 'object';

    if ($this->class) $string .= '('.$this->class.')';

    return $string;
  }

  /**
   * @var string
   */
  protected $class;
}