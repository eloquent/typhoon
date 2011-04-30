<?php

namespace Typhoon\Exception;

abstract class Exception extends \Exception
{
  public function __construct(\Exception $previous = null)
  {
    $this->previous = $previous;
  }

  /**
   * @return \Exception
   */
  public function previous()
  {
    return $this->previous;
  }

  /**
   * @var \Exception
   */
  protected $previous;
}