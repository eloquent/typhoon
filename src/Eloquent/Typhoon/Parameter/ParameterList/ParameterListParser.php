<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Parameter\ParameterList;

use Eloquent\Typhoon\Documentation\AST\DocumentationBlock;

interface ParameterListParser
{
  /**
   * @param DocumentationBlock $documentationBlock
   *
   * @return ParameterList
   */
  public function parseDocumentationBlock(DocumentationBlock $documentationBlock);
}
