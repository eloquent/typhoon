<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Test\Fixture;

class Stringable
{
  /**
   * @param string $string
   */
  public function __construct($string = null)
  {
    if (null === $string)
    {
      $string = __CLASS__;
    }
    
    $this->string = $string;
  }
  
  /**
   * @return string
   */
  public function __toString()
  {
    return $this->string;
  }
  
  /**
   * @var string
   */
  public $string;
}
