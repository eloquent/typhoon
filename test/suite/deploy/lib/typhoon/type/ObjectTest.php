<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon\Type;

use stdClass;
use Typhoon\Test\TypeTestCase;

class ObjectTest extends TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $attributes = array('class' => 'stdClass');

    return array(
      // object of any class
      array(false, null),                      // #0: null
      array(false, true),                      // #1: boolean
      array(false, 'string'),                  // #2: string
      array(false, 1),                         // #3: integer
      array(false, .1),                        // #4: float
      array(false, array()),                   // #5: array
      array(true,  new stdClass),              // #6: object
      array(true,  function(){}),              // #7: closure
      array(false, $this->resourceFixture()),  // #8: resource

      // object of a specific class
      array(true,  new stdClass,             $attributes),  // #9: object of correct class
      array(false, new Object,               $attributes),  // #10: object of incorrect class
      array(false, null,                     $attributes),  // #11: null
      array(false, true,                     $attributes),  // #12: boolean
      array(false, 'string',                 $attributes),  // #13: string
      array(false, 1,                        $attributes),  // #14: integer
      array(false, .1,                       $attributes),  // #15: float
      array(false, array(),                  $attributes),  // #16: array
      array(false, function(){},             $attributes),  // #17: closure
      array(false, $this->resourceFixture(), $attributes),  // #18: resource
    );
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\Object';
  }

  /**
   * @covers Typhoon\Type\Object::setTyphoonAttribute
   * @covers Typhoon\Type\Object::attributeSupported
   */
  public function testSetTyphoonAttribute()
  {
    $type = $this->typeFixture();
    $type->setTyphoonAttribute('class', 'foo');

    $this->assertEquals('foo', $type->attribute('class'));
  }

  /**
   * @covers Typhoon\Type\Object::setTyphoonAttribute
   */
  public function testSetTyphoonAttributeFailure()
  {
    $type = $this->typeFixture();
    $this->setExpectedException('Typhoon\Assertion\Exception\UnexpectedArgument');
    $type->setTyphoonAttribute('class', 1);
  }

  // methods below must be manually overridden to implement @covers
  
  /**
   * @covers Typhoon\Type\Object::typhoonCheck
   * @dataProvider typeValues
   * @group typhoon_types
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }
}