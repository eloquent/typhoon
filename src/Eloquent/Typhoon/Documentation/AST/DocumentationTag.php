<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Documentation\AST;

use Eloquent\Typhoon\Primitive\String;

class DocumentationTag
{
  public function __construct(String $name, String $content)
  {
    $this->name = $name->value();
    $this->content = $content->value();
  }

  /**
   * @return string
   */
  public function name()
  {
    return $this->name;
  }

  /**
   * @return string
   */
  public function content()
  {
    return $this->content;
  }

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $content;
}
