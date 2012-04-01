<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Composite;

use Eloquent\Typhax\Lexer\Token;

class AndType extends BaseCompositeType
{
  /**
   * @param mixed $value
   *
   * @return boolean
   */
  public function typhoonCheck($value)
  {
    foreach ($this->types as $type)
    {
      if (!$type->typhoonCheck($value))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * @return string
   */
  public function operator()
  {
    return Token::TOKEN_AND;
  }
}
