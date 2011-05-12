<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Typhoon;
use Typhoon\Primitive\String;
use Typhoon\Type\Exception\UnsupportedAttribute;

abstract class StandardDynamicType implements DynamicType
{
  /**
   * @param string $attribute
   * @param mixed $value
   */
  public function setTyphoonAttribute($attribute, $value)
  {
    $this->assertAttribute($attribute);

    $this->attributes[$attribute] = $value;
  }

  /**
   * @return array
   */
  public function typhoonAttributes()
  {
    return $this->attributes;
  }

  /**
   * @param string $attribute
   *
   * @return boolean
   */
  public function hasAttribute($attribute)
  {
    $this->assertAttribute($attribute);

    return array_key_exists($attribute, $this->attributes);
  }

  /**
   * @param string $attribute
   * @param mixed $default
   *
   * @return mixed
   */
  public function attribute($attribute, $default = null)
  {
    $this->assertAttribute($attribute);

    return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : $default;
  }

  /**
   * @param string $attribute
   */
  protected function assertAttribute($attribute)
  {
    new String($attribute);

    if (!$this->attributeSupported($attribute))
    {
      throw new UnsupportedAttribute(
        new String(Typhoon::instance()->typeRenderer()->render($this)),
        new String($attribute)
      );
    }
  }

  /**
   * @param string $attribute
   *
   * @return boolean
   */
  abstract protected function attributeSupported($attribute);

  /**
   * @var array
   */
  protected $attributes = array();
}