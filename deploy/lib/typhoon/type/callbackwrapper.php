<?php

namespace Typhoon\Type;

use Typhoon\Type;

class CallbackWrapper implements Type
{
  /**
   * @param mixed value
   *
   * @return boolean
   */
  public function check($value)
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
   * @param callback $callback
   */
  public function setCallback($callback)
  {
    $this->callback = $callback;
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