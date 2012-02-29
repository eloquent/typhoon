<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

class SocketType extends BaseType
{
  public function __construct()
  {
    $this->innerType = new ResourceType(array(
      ResourceType::ATTRIBUTE_TYPE => ResourceType::TYPE_SOCKET,
    ));
  }

  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    return $this->innerType->typhoonCheck($value);
  }

  /**
   * @var ResourceType
   */
  protected $innerType;
}
