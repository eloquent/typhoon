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

class DocumentationTagTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationTag::__construct
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationTag::name
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationTag::content
   * @group documentation
   */
  public function testTag()
  {
    $name = new String('foo');
    $content = new String('bar');
    $tag = new DocumentationTag($name, $content);

    $this->assertSame('foo', $tag->name());
    $this->assertSame('bar', $tag->content());
  }
}
