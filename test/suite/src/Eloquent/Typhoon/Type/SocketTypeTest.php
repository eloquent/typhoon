<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type;

use Eloquent\Typhax\IntrinsicType\IntrinsicTypeName;
use Eloquent\Typhoon\Attribute\AttributeSignature;
use Eloquent\Typhoon\Primitive\String;
use ReflectionObject;
use stdClass;

class SocketTypeTest extends \Eloquent\Typhoon\Test\TypeTestCase
{
  /**
   * @return array
   */
  public function typeValues()
  {
    $typeValues = array(
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
      array(false, $this->directoryFixture()), // #11: directory
    );

    if (extension_loaded('sockets'))
    {
      $typeValues[] = array(true, $this->socketFixture()); // #12: socket
    }

    return $typeValues;
  }

  /**
   * @return string
   */
  protected function typeClass()
  {
    return __NAMESPACE__.'\SocketType';
  }

  /**
   * @return string
   */
  protected function typeName()
  {
    return IntrinsicTypeName::NAME_SOCKET()->value();
  }

  // methods below must be manually overridden to implement @covers

  /**
   * @covers Eloquent\Typhoon\Type\SocketType::__construct
   * @covers Eloquent\Typhoon\Type\SocketType::typhoonCheck
   * @dataProvider typeValues
   * @group types
   * @group type
   * @group dynamic-type
   */
  public function testTyphoonCheck($expected, $value, $attributes = null) { parent::testTyphoonCheck($expected, $value, $attributes); }

  /**
   * @covers Eloquent\Typhoon\Type\SocketType::typhoonName
   * @group types
   */
  public function testTyphoonName() { parent::testTyphoonName(); }
}
