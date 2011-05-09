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
use Typhoon\TypeRegistry;

class Typhoon
{
  /**
   * @return Typhoon
   */
  public static function instance()
  {
    if (!self::$instance) self::$instance = new self;

    return self::$instance;
  }

  /**
   * @param Typhoon $instance
   */
  public static function install(self $instance)
  {
    self::$instance = $instance;
  }

  /**
   * @return TypeAssertion
   */
  public function typeAssertion()
  {
    return new TypeAssertion;
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
    if (!$this->typeRegistry) $this->typeRegistry = new TypeRegistry;

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
    if (!$this->typeRenderer) $this->typeRenderer = new Typhax;

    return $this->typeRenderer;
  }

  /**
   * @var Tyhpoon
   */
  private static $instance;

  /**
   * @var TypeRegistry
   */
  protected $typeRegistry;

  /**
   * @var Typhax
   */
  protected $typeRenderer;
}