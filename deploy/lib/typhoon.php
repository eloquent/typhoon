<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Typhoon\Primitive\String;
use Typhoon\TypeRegistry;

class Typhoon
{
  /**
   * @return Typhoon
   */
  public static function instance()
  {
    static $instance;
    if (!$instance) $instance = new self;

    return $instance;
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
   * @var TypeRegistry
   */
  protected $typeRegistry;
}