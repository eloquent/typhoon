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

use Eloquent\Typhoon\Assertion\ParameterListAssertion;
use Eloquent\Typhoon\Documentation\TyphoonDocumentationParser;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;
use Eloquent\Typhoon\Typhax\TyphaxParameterListParser;
use Eloquent\Typhoon\Typhax\TyphaxTranscompiler;
use ReflectionMethod;

class Typhoon
{
  /**
   * @return Typhoon
   */
  public static function instance()
  {
    if (!self::$instance)
    {
      self::install(new self);
    }

    return self::$instance;
  }

  public static function install(self $instance)
  {
    self::$instance = $instance;
  }

  public static function uninstall()
  {
    self::$instance = null;
  }

  /**
   * @var Tyhpoon
   */
  private static $instance;
}
