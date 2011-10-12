<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

class DirectoryType extends BaseType
{
  public function __construct()
  {
    $this->innerType = new NodeType(array(
      NodeType::ATTRIBUTE_TYPE => NodeType::TYPE_DIRECTORY,
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
   * @var NodeType
   */
  protected $innerType;
}