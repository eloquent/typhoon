<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type;

use Ezzatron\Typhoon\Attribute\Attributes;
use stdClass;

class DirectoryTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(false, new stdClass),              // #6: object
      array(false, function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource
      array(false, $this->streamFixture()),    // #9: stream
      array(false, $this->fileFixture()),      // #10: file
      array(true,  $this->directoryFixture()), // #11: directory
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\DirectoryType';
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\DirectoryType::__construct
   * @covers Ezzatron\Typhoon\Type\DirectoryType::typhoonCheck
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, Attributes $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}