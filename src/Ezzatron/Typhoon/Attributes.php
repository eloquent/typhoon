<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Ezzatron\Typhoon\Attributes\Exception\UnsupportedAttribute;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\String as StringType;

class Attributes extends Collection
{
  /**
   * @param AttributeSignature $signature
   */
  public function setSignature(AttributeSignature $signature)
  {
    $this->signature = $signature;
  }

  /**
   * @return AttributeSignature
   */
  public function signature()
  {
    if (!$this->signature)
    {
      $this->signature = new AttributeSignature;
    }

    return $this->signature;
  }

  /**
   * @return Type
   */
  protected function keyType()
  {
    return new StringType;
  }

  /**
   * @param mixed $key
   *
   * @return Type
   */
  protected function valueType($key)
  {
    return $this->signature[$key];
  }
  
  /**
   * @param mixed $key
   * @param boolean $allowNull
   */
  protected function assertKey($key, $allowNull)
  {
    parent::assertKey($key, $allowNull);
    
    if (!isset($this->signature[$key]))
    {
      throw new UnsupportedAttribute(
        new String(get_class($this))
        , new String($key)
      );
    }
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKey()
  {
    return false;
  }

  /**
   * @var AttributeSignature
   */
  protected $signature;
}