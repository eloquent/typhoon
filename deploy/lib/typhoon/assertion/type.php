<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Assertion;

use Typhoon\Assertion;
use Typhoon\Primitive\String;
use Typhoon\Type as TypeObject;
use Typhoon\Type\Exception\UnexpectedType;

class Type implements Assertion
{
  /**
   * @param TypeObject $type
   * @param type $value
   */
  public function __construct(TypeObject $type, $value)
  {
    $this->type = $type;
    $this->value = $value;
  }
  
  public function assert()
  {
    if ($this->type->check($this->value)) return;

    throw new UnexpectedType($this->value, new String((string)$this->type));
  }

  /**
   * @var TypeObject
   */
  protected $type;

  /**
   * @var mixed
   */
  protected $value;
}