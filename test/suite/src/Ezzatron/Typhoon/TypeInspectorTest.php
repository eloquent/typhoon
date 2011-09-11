<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon;

use Phake;
use stdClass;
use Ezzatron\Typhoon\Test\TestCase;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\Boolean;
use Ezzatron\Typhoon\Type\Float;
use Ezzatron\Typhoon\Type\Integer;
use Ezzatron\Typhoon\Type\Mixed;
use Ezzatron\Typhoon\Type\Null;
use Ezzatron\Typhoon\Type\Object;
use Ezzatron\Typhoon\Type\String;
use Ezzatron\Typhoon\Type\Resource;
use Ezzatron\Typhoon\Type\Traversable;

class TypeInspectorTest extends TestCase
{
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
      array(.1,       new Float),      // #4: Integer
      array('foo',    new String),     // #5: String
    );

    // #6: Resource
    $value = stream_context_create();
    $expected = new Resource;
    $data[] = array($value, $expected);

    // #7: Array
    $value = array();
    $expected = new ArrayType;
    $data[] = array($value, $expected);

    // #8: Traversable
    $value = Phake::mock('Iterator');
    $expected = new Traversable;
    $expected->typhoonAttributes();
    $data[] = array($value, $expected);

    // #9: Object (stdClass)
    $value = new stdClass;
    $expected = new Object;
    $expected->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, 'stdClass');
    $data[] = array($value, $expected);

    // #10: Object (Typhoon)
    $value = Typhoon::instance();
    $expected = new Object;
    $expected->typhoonAttributes()->set(Object::ATTRIBUTE_INSTANCE_OF, 'Ezzatron\Typhoon\Typhoon');
    $data[] = array($value, $expected);

    return $data;
  }

  protected function setUp()
  {
    $this->_typeInspector = new TypeInspector;
  }

  /**
   * @covers Ezzatron\Typhoon\TypeInspector::typeOf
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