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

class TyphoonDocumentationParserTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function blockCommentData()
  {
    $data = array();

    // #0: Empty block
    $blockComment = <<<EOD
/**
 *
 */
EOD;
    $expected = new AST\DocumentationBlock;
    $data[] = array($expected, $blockComment);

    // #1: Standard block
    $blockComment = <<<EOD
/**
 * This is the summary.
 *   This is also the summary.
 *
 * This is the body.
 *
 *   This is also the body.
 *
 * @foo bar baz
 * @foo qux doom
 * @splat boing
 * This is ignored.
 * This is also ignored.
 */
EOD;
    $tags = new AST\DocumentationTags;
    $tags->set(NULL, new AST\DocumentationTag(new String('foo'), new String('bar baz')));
    $tags->set(NULL, new AST\DocumentationTag(new String('foo'), new String('qux doom')));
    $tags->set(NULL, new AST\DocumentationTag(new String('splat'), new String('boing')));
    $summary = new String(<<<EOD
This is the summary.
  This is also the summary.
EOD
    );
    $body = new String(<<<EOD
This is the body.

  This is also the body.
EOD
    );
    $expected = new AST\DocumentationBlock($tags, $summary, $body);
    $data[] = array($expected, $blockComment);

    // #2: Summary only
    $blockComment = <<<EOD
/**
 * This is the summary.
 *   This is also the summary.
 *
 */
EOD;
    $summary = new String(<<<EOD
This is the summary.
  This is also the summary.
EOD
    );
    $expected = new AST\DocumentationBlock(null, $summary);
    $data[] = array($expected, $blockComment);

    // #3: Body only
    $blockComment = <<<EOD
/**
 *
 * This is the body.
 *
 *   This is also the body.
 */
EOD;
    $body = new String(<<<EOD
This is the body.

  This is also the body.
EOD
    );
    $expected = new AST\DocumentationBlock(null, null, $body);
    $data[] = array($expected, $blockComment);

    // #4: Tags only
    $blockComment = <<<EOD
/**
 * @foo bar baz
 * @foo qux doom
 * @splat boing
 * This is ignored.
 * This is also ignored.
 */
EOD;
    $tags = new AST\DocumentationTags;
    $tags->set(NULL, new AST\DocumentationTag(new String('foo'), new String('bar baz')));
    $tags->set(NULL, new AST\DocumentationTag(new String('foo'), new String('qux doom')));
    $tags->set(NULL, new AST\DocumentationTag(new String('splat'), new String('boing')));
    $expected = new AST\DocumentationBlock($tags);
    $data[] = array($expected, $blockComment);

    return $data;
  }

  /**
   * @covers Eloquent\Typhoon\Documentation\TyphoonDocumentationParser
   * @covers Eloquent\Typhoon\Documentation\DocumentationParser
   * @dataProvider blockCommentData
   * @group documentation
   */
  public function testParseBlockComment(AST\DocumentationBlock $expected, $blockComment)
  {
    $parser = new TyphoonDocumentationParser;

    $this->assertEquals($expected, $parser->parseBlockComment($blockComment));
  }
}
