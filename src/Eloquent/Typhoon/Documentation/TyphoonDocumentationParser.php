<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Documentation;

use Eloquent\Typhoon\Primitive\String;

class TyphoonDocumentationParser implements DocumentationParser
{
  /**
   * @param string $blockComment
   *
   * @return DocumentationBlock
   */
  public function parseBlockComment($blockComment)
  {
    $blockCommentLines = $this->parseBlockCommentLines($blockComment);

    return new AST\DocumentationBlock(
      $this->parseBlockCommentTags($blockCommentLines)
      , $this->parseBlockCommentSummary($blockCommentLines)
      , $this->parseBlockCommentBody($blockCommentLines)
    );
  }

  /**
   * @param string $blockComment
   *
   * @return array
   */
  protected function parseBlockCommentLines($blockComment)
  {
    $lines = array();
    if (preg_match_all(static::PATTERN_LINES, $blockComment, $matches))
    {
      $lines = $matches[1];
    }

    return $lines;
  }

  /**
   * @param array $blockCommentLines
   *
   * @return DocumentationTags
   */
  protected function parseBlockCommentTags(array &$blockCommentLines)
  {
    $tags = new AST\DocumentationTags;
    foreach ($blockCommentLines as $index => $blockCommentLine)
    {
      if (preg_match(static::PATTERN_TAG, $blockCommentLine, $matches))
      {
        $tags->set(null, new AST\DocumentationTag(
          new String($matches[1])
          , new String($matches[2])
        ));
      }

      if ($tags->count() > 0)
      {
        unset($blockCommentLines[$index]);
      }
    }

    return $tags;
  }

  /**
   * @param array $blockCommentLines
   *
   * @return String|null
   */
  protected function parseBlockCommentSummary(array &$blockCommentLines)
  {
    $summary = '';
    foreach ($blockCommentLines as $index => $blockCommentLine)
    {
      if ('' === trim($blockCommentLine))
      {
        break;
      }
      
      $summary .= $blockCommentLine."\n";

      unset($blockCommentLines[$index]);
    }

    if ('' === $summary)
    {
      $summary = null;
    }
    else
    {
      $summary = new String(trim($summary));
    }

    return $summary;
  }

  /**
   * @param array $blockCommentLines
   *
   * @return String|null
   */
  protected function parseBlockCommentBody(array $blockCommentLines)
  {
    $body = '';
    foreach ($blockCommentLines as $index => $blockCommentLine)
    {
      $body .= $blockCommentLine."\n";
    }

    if ('' === $body)
    {
      $body = null;
    }
    else
    {
      $body = new String(trim($body));
    }

    return $body;
  }

  const PATTERN_LINES = '~^\s*\* ?(?!/)(.*)$~m';
  const PATTERN_TAG = '~^@(\w+) (.*)$~';
}
