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

class DocumentationBlock
{
  public function __construct(DocumentationTags $tags = null, String $summary = null, String $body = null)
  {
    if (null === $tags)
    {
      $tags = new DocumentationTags;
    }
    if (null !== $summary)
    {
      $summary = $summary->value();
    }
    if (null !== $body)
    {
      $body = $body->value();
    }

    $this->tags = $tags;
    $this->summary = $summary;
    $this->body = $body;
  }

  /**
   * @return DocumentationTags
   */
  public function tags()
  {
    return $this->tags;
  }

  /**
   * @return string|null
   */
  public function summary()
  {
    return $this->summary;
  }

  /**
   * @return string|null
   */
  public function body()
  {
    return $this->body;
  }

  /**
   * @var DocumentationTags
   */
  protected $tags;

  /**
   * @var string|null
   */
  protected $summary;

  /**
   * @var string|null
   */
  protected $body;
}
