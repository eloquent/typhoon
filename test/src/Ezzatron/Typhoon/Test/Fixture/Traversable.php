<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Test\Fixture;

use ArrayIterator;
use IteratorAggregate;

class Traversable implements IteratorAggregate
{
  /**
   * @param array $values
   */
  public function __construct(array $values = null)
  {
    if (null === $values)
    {
      $values = array();
    }

    $this->values = $values;
  }

  /**
   * @return ArrayIterator
   */
  public function getIterator()
  {
    return new ArrayIterator($this->values);
  }

  /**
   * @var array
   */
  public $values;
}
