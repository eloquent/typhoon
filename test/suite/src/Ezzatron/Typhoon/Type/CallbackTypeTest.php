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

use stdClass;

class CallbackTypeTest extends \Ezzatron\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    return array(
      array(false, null),                          // #0: null
      array(false, true),                          // #1: boolean
      array(false, 'string'),                      // #2: string
      array(false, 1),                             // #3: integer
      array(false, .1),                            // #4: float
      array(false, array()),                       // #5: array
      array(false, new stdClass),                  // #6: object
      array(true,  function(){}),                  // #7: closure
      array(false, $this->resourceFixture()),      // #8: resource

      array(true,  'strtolower'),                                   // #9: function name
      array(true,  array($this, 'typeValues')),                     // #10: method name
      array(true,  array('Ezzatron\Typhoon\Typhoon', 'instance')),  // #10: static method name
      array(true,  'Ezzatron\Typhoon\Typhoon::instance'),           // #11: static method name
      array(true,  $this->callableFixture()),                       // #12: object with __invoke method
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\CallbackType';
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Ezzatron\Typhoon\Type\CallbackType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}
