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

class Callable
{
  /**
   * @param mixed $return
   */
  public function __construct($return = null)
  {
    $this->return = $return;
  }

  /**
   * @return mixed
   */
  public function __invoke()
  {
    if (null === $this->return) {
      $return = __METHOD__.'(';

      foreach (func_get_args() as $argument) {
        $return .= var_export($argument, true);
      }

      $return .')';

      return $return;
    }

    return $this->return;
  }

  /**
   * @var mixed
   */
  public $return;
}