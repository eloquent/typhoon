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
use Eloquent\Typhoon\Type\Renderer\TyphaxTypeRenderer;

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
   * @param TypeInspector $typeInspector
   */
  public function setTypeInspector(TypeInspector $typeInspector)
  {
    $this->typeInspector = $typeInspector;
  }

  /**
   * @return TypeInspector
   */
  public function typeInspector()
  {
    if (!$this->typeInspector)
    {
      $this->typeInspector = new TypeInspector;
    }

    return $this->typeInspector;
  }

  /**
   * @param TypeRegistry $typeRegistry
   */
  public function setTypeRegistry(TypeRegistry $typeRegistry)
  {
    $this->typeRegistry = $typeRegistry;
  }

  /**
   * @return TypeRegistry
   */
  public function typeRegistry()
  {
    if (!$this->typeRegistry)
    {
      $this->typeRegistry = new TypeRegistry;
    }

    return $this->typeRegistry;
  }

  /**
   * @param TypeRenderer $typeRenderer
   */
  public function setTypeRenderer(TypeRenderer $typeRenderer)
  {
    $this->typeRenderer = $typeRenderer;
  }

  /**
   * @return TypeRenderer
   */
  public function typeRenderer()
  {
    if (!$this->typeRenderer)
    {
      $this->typeRenderer = new TyphaxTypeRenderer;
    }

    return $this->typeRenderer;
  }

  /**
   * @var Tyhpoon
   */
  private static $instance;

  /**
   * @var TypeInspector
   */
  protected $typeInspector;

  /**
   * @var TypeRegistry
   */
  protected $typeRegistry;

  /**
   * @var TyphaxTypeRenderer
   */
  protected $typeRenderer;
}
