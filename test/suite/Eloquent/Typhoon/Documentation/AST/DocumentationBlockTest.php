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

class DocumentationBlockTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationBlock::__construct
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationBlock::tags
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationBlock::summary
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationBlock::body
   * @group documentation
   */
  public function testBlock()
  {
    $block = new DocumentationBlock;

    $this->assertInstanceOf(__NAMESPACE__.'\DocumentationTags', $block->tags());
    $this->assertNull($block->summary());
    $this->assertNull($block->body());

    $tags = new DocumentationTags;
    $summary = new String('foo');
    $body = new String('bar');
    $block = new DocumentationBlock($tags, $summary, $body);

    $this->assertSame($tags, $block->tags());
    $this->assertSame('foo', $block->summary());
    $this->assertSame('bar', $block->body());
  }
}
