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

use Ezzatron\Typhoon\Attribute\Attributes;

class FileType extends BaseType
{
  public function __construct()
  {
    $this->innerType = new NodeType(new Attributes(array(
      NodeType::ATTRIBUTE_TYPE => NodeType::TYPE_FILE,
    )));
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