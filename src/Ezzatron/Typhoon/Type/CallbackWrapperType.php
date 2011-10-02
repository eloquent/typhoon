<?php

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Primitive\Callback as CallbackPrimitive;

class CallbackWrapperType extends BaseType
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    return call_user_func_array(
      $this->callback(),
      array_merge(
        array($value),
        $this->arguments()
      )
    );
  }

  /**
   * @param CallbackPrimitive $callback
   */
  public function setCallback(CallbackPrimitive $callback)
  {
    $this->callback = $callback->value();
  }

  /**
   * @return callback
   */
  public function callback()
  {
    if (!$this->callback)
    {
      $this->callback = function() { return true; };
    }

    return $this->callback;
  }

  /**
   * @param array $arguments
   */
  public function setArguments(array $arguments)
  {
    $this->arguments = $arguments;
  }

  /**
   * @return array
   */
  public function arguments()
  {
    return $this->arguments;
  }

  /**
   * @var callback
   */
  protected $callback;

  /**
   * @var array
   */
  protected $arguments = array();
}