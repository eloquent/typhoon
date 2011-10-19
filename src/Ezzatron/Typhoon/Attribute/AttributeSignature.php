<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Attribute;

use Ezzatron\Typhoon\Collection\Collection;
use Ezzatron\Typhoon\Primitive\Boolean;
use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\TypeType;

class AttributeSignature extends Collection
{
  /**
   * @param String $holderName
   */
  public function setHolderName(String $holderName)
  {
    $this->holderName = $holderName->value();
  }

  /**
   * @return string
   */
  public function holderName()
  {
    return $this->holderName;
  }
  
  /**
   * @param integer|string $key
   * @param mixed $value
   * @param Boolean $required
   */
  public function set($key, $value, Boolean $required = null)
  {
    if (null === $required)
    {
      $required = false;
    }
    else
    {
      $required = $required->value();
    }
    
    $this->required[$key] = $required;

    parent::set($key, $value);
  }

  /**
   * @param integer|string $key
   */
  public function remove($key)
  {
    parent::remove($key);

    unset($this->required[$key]);
  }

  /**
   * @param type $key
   *
   * @return boolean
   */
  public function isRequired($key)
  {
    $this->assertKeyExists($key);
    
    return $this->required[$key];
  }

  /**
   * @return Type
   */
  protected function keyType()
  {
    return new StringType;
  }

  /**
   * @return boolean
   */
  protected function allowEmptyKeyForSet()
  {
    return false;
  }

  /**
   * @param mixed $key
   *
   * @return Type
   */
  protected function valueType($key)
  {
    return new TypeType;
  }

  /**
   * @var string
   */
  protected $holderName;

  /**
   * @var array
   */
  protected $required = array();
}