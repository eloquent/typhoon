<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Typhoon;
use Typhoon\Primitive\String as StringPrimitive;
use Typhoon\Renderer\Type as TypeRenderer;
use Typhoon\Renderer\Type\Typhax;
use Typhoon\Type\Exception\UnexpectedType;
use Typhoon\TypeRegistry\Exception\UnregisteredType;

abstract class Type
{
  final public function __construct()
  {
    $this->arguments = func_get_args();

    call_user_func_array(array($this, 'construct'), $this->arguments);
  }

  public function construct() {}

  /**
   * @return array
   */
  final public function arguments()
  {
    return $this->arguments;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    try
    {
      return $this->renderer()->render($this);
    }
    catch (UnregisteredType $e)
    {
      return 'unregistered type ('.get_class($this).')';
    }
  }

  /**
   * @param TypeRenderer $renderer
   */
  public function setRenderer(TypeRenderer $renderer)
  {
    $this->renderer = $renderer;
  }

  /**
   * @return TypeRenderer
   */
  public function renderer()
  {
    if (!$this->renderer) $this->renderer = Typhoon::instance()->typeRenderer();

    return $this->renderer;
  }

  /**
   * @param mixed value
   * 
   * @return boolean
   */
  abstract public function check($value);

  /**
   * @var array
   */
  private $arguments;

  /**
   * @var TypeRenderer
   */
  protected $renderer;
}