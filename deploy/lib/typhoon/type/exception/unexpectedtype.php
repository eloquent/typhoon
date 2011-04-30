<?php

namespace Typhoon\Type\Exception;

final class UnexpectedType extends Exception
{
  /**
   * @return string
   */
  protected function generateMessage()
  {
    return 'Unexpected type.';
  }
}