<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Renderer\Type;

use Typhoon\DynamicType;
use Typhoon\Renderer\Type as TypeRenderer;
use Typhoon\Type as Type;
use Typhoon\TypeRegistry\Exception\UnregisteredType;

class Typhax extends TypeRenderer
{
  /**
   * @param Type $type
   *
   * @return string
   */
  public function render(Type $type)
  {
    $rendered = $this->renderAlias($type);
    if ($type instanceof DynamicType) $rendered .= $this->renderAttributes($type);

    return $rendered;
  }

  /**
   * @param Type $type
   *
   * @return string
   */
  protected function renderAlias(Type $type)
  {
    try
    {
      return $this->typeRegistry()->alias($type);
    }
    catch (UnregisteredType $e) {}
    
    return 'unregistered_type<'.get_class($type).'>';
  }

  /**
   * @param DynamicType $type
   *
   * @return string
   */
  protected function renderAttributes(DynamicType $type)
  {
    if (!$attributes = $type->typhoonAttributes()) return '';

    $rendered = '';

    foreach ($attributes as $key => $value)
    {
      $rendered .= $rendered ? ', ' : '(';
      $rendered .= $this->renderAttribute($key, $value);
    }

    $rendered .= $rendered ? ')' : '';

    return $rendered;
  }

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return string
   */
  protected function renderAttribute($key, $value)
  {
    return $key.'='.var_export($value, true);
  }
}