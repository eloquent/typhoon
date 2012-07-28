<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Primitive;

class DirectoryTest extends \Eloquent\Typhoon\Test\TestCase
{
  /**
   * @covers Eloquent\Typhoon\Primitive\Directory::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new Directory($this->directoryFixture());
    $this->assertInstanceOf('Eloquent\Typhoon\Type\DirectoryType', $primitive->type());
  }
}
