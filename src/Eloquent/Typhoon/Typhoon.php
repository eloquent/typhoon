<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon;

use Eloquent\Typhoon\Type\Inspector\TypeInspector;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Eloquent\Typhoon\Type\Renderer\TypeRenderer;
use Eloquent\Typhoon\Typhax\TyphaxTypeRenderer;

class Typhoon
{
  /**
   * @return Typhoon
   */
  public static function instance()
  {
    if (!self::$instance)
    {
      self::$instance = new self;
    }

    return self::$instance;
  }

  public static function uninstall()
  {
    self::$instance = null;
  }

  public function install()
  {
    self::$instance = $this;
  }

  /**
   * @var Tyhpoon
   */
  private static $instance;
}
