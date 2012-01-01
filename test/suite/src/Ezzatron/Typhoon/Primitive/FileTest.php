<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Primitive;

class FileTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @covers Ezzatron\Typhoon\Primitive\File::type
   * @group primitives
   * @group primitive
   */
  public function testType()
  {
    $primitive = new File($this->fileFixture());
    $this->assertInstanceOf('Ezzatron\Typhoon\Type\FileType', $primitive->type());
  }
}
