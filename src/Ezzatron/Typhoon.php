<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Typhoon\Assertion\Type as TypeAssertion;
use Typhoon\Renderer\Type as TypeRenderer;
use Typhoon\Renderer\Type\Typhax;
use Typhoon\TypeInspector;
use Typhoon\TypeRegistry;

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
   * @return TypeAssertion
   */
  public function typeAssertion()
  {
    return new TypeAssertion;
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
      $this->typeRenderer = new Typhax;
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
   * @var Typhax
   */
  protected $typeRenderer;
}