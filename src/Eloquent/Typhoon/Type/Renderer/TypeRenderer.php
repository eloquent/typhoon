<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Renderer;

use Eloquent\Typhoon\Typhoon;
use Eloquent\Typhoon\Type\Type;
use Eloquent\Typhoon\Type\Registry\TypeRegistry;

abstract class TypeRenderer
{
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
      $this->typeRegistry = Typhoon::instance()->typeRegistry();
    }

    return $this->typeRegistry;
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  abstract public function render(Type $type);

  /**
   * @var TypeRegistry
   */
  protected $typeRegistry;
}
