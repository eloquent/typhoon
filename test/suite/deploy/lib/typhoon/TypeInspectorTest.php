<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Typhoon;

use Phake;
use stdClass;
use Typhoon;
use Typhoon\Test\TestCase;
use Typhoon\Type\ArrayType;
use Typhoon\Type\Boolean;
use Typhoon\Type\Integer;
use Typhoon\Type\Mixed;
use Typhoon\Type\Null;
use Typhoon\Type\Object;
use Typhoon\Type\String;
use Typhoon\Type\Traversable;

class TypeInspectorTest extends TestCase
{
  protected function setUp()
  {
    $this->_typeInspector = new TypeInspector;
  }

  /**
   * @return array
   */
  public function typeOfData()
  {
    $data = array(
      array(null,     new Null),       // #0: Null
      array(true,     new Boolean),    // #1: Boolean
      array(false,    new Boolean),    // #2: Boolean
      array(1,        new Integer),    // #3: Integer
      array('foo',    new String),     // #4: String
    );

    // #5: Array
    $value = array();
    $expected = new ArrayType;
    $data[] = array($value, $expected);

    // #6: Traversable
    $value = Phake::mock('Iterator');
    $expected = new Traversable;
    $data[] = array($value, $expected);

    // #7: Object (stdClass)
    $value = new stdClass;
    $expected = new Object;
    $expected->setTyphoonAttribute(Object::ATTRIBUTE_CLASS, 'stdClass');
    $data[] = array($value, $expected);

    // #8: Object (Typhoon)
    $value = Typhoon::instance();
    $expected = new Object;
    $expected->setTyphoonAttribute(Object::ATTRIBUTE_CLASS, 'Typhoon');
    $data[] = array($value, $expected);

    return $data;
  }

  /**
   * @covers Typhoon\TypeInspector::typeOf
   * @dataProvider typeOfData
   */
  public function testTypeOf($value, $expected)
  {
    $this->assertEquals($expected, $this->_typeInspector->typeOf($value));
  }

  /**
   * @var TypeInspector
   */
  protected $_typeInspector;
}