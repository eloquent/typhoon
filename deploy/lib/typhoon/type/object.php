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

use Typhoon\Primitive\String;
use Typhoon\StandardDynamicType;

class Object extends StandardDynamicType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
  {
    if ($class = $this->attribute('class'))
    {
      return $value instanceof $class;
    }

    return is_object($value);
  }

  /**
   * @param string $attribute
   * @param mixed $value
   */
  public function setTyphoonAttribute($attribute, $value)
  {
    new String($value);
    
    parent::setTyphoonAttribute($attribute, $value);
  }

  /**
   * @param string $attribute
   *
   * @return boolean
   */
  protected function attributeSupported($attribute)
  {
    return 'class' == $attribute;
  }
}