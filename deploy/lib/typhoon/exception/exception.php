<?php

namespace Typhoon\Exception;

abstract class Exception extends \Exception
{
  public function __construct()
  {
    $this->message = $this->generateMessage();
  }

  public function setPrevious(\Exception $previous)
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
   * @return string
   */
  abstract protected function generateMessage();

  /**
   * @var \Exception
   */
  protected $previous;
}