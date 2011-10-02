<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Renderer;

use Ezzatron\Typhoon\Primitive\String;
use Ezzatron\Typhoon\Type\Dynamic\DynamicType;
use Ezzatron\Typhoon\Type\Registry\Exception\UnregisteredTypeException;
use Ezzatron\Typhoon\Type\Type;

class TyphaxTypeRenderer extends TypeRenderer
{
  /**
   * @param Type $type
   *
   * @return string
   */
  public function render(Type $type)
  {
    $rendered = $this->renderAlias($type);

    if ($type instanceof DynamicType)
    {
      $rendered .= $this->renderAttributes($type);
    }

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
    catch (UnregisteredTypeException $e) {}
    
    return 'unregistered_type<'.get_class($type).'>';
  }

  /**
   * @param DynamicType $type
   *
   * @return string
   */
  protected function renderAttributes(DynamicType $type)
  {
    if (!$attributes = $type->typhoonAttributes())
    {
      return '';
    }

    $rendered = '';

    foreach ($attributes as $key => $value)
    {
      $rendered .= $rendered ? ', ' : '(';
      $rendered .= $this->renderAttribute(new String($key), $value);
    }

    $rendered .= $rendered ? ')' : '';

    return $rendered;
  }

  /**
   * @param String $key
   * @param mixed $value
   *
   * @return string
   */
  protected function renderAttribute(String $key, $value)
  {
    return $key.'='.var_export($value, true);
  }
}