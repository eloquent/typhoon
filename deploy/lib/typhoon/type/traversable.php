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

use Typhoon\BaseTraversableType;
use Typhoon\OrType;
use Typhoon\Type\ArrayType;
use Typhoon\Type\Object;

class Traversable extends BaseTraversableType
{
  public function __construct()
  {
    $this->primaryType = $this->primaryType();
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  protected function checkPrimary($value)
  {
    return $this->primaryType()->typhoonCheck($value);
  }

  /**
   * @return Type
   */
  protected function primaryType()
  {
    $traversableObject = new Object;
    $traversableObject->setTyphoonAttribute(Object::ATTRIBUTE_CLASS, 'Traversable');

    $primaryType = new OrType;
    $primaryType->addTyphoonType(new ArrayType);
    $primaryType->addTyphoonType($traversableObject);

    return $primaryType;
  }

  /**
   * @var Type
   */
  protected $primaryType;
}