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

class OrType extends BaseCompositeType
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
      if ($type->typhoonCheck($value))
      {
        return true;
      }
    }

    return false;
  }

  /**
   * @return string
   */
  public function typhoonOperator()
  {
    return Token::TOKEN_PIPE;
  }
}
