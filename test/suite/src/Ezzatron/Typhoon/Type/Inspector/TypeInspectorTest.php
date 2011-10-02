<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\Typhoon\Type\Inspector;

use Phake;
use stdClass;
use Ezzatron\Typhoon\Type\ArrayType;
use Ezzatron\Typhoon\Type\BooleanType;
use Ezzatron\Typhoon\Type\FloatType;
use Ezzatron\Typhoon\Type\IntegerType;
use Ezzatron\Typhoon\Type\MixedType;
use Ezzatron\Typhoon\Type\NullType;
use Ezzatron\Typhoon\Type\ObjectType;
use Ezzatron\Typhoon\Type\StringType;
use Ezzatron\Typhoon\Type\ResourceType;
use Ezzatron\Typhoon\Type\TraversableType;
use Ezzatron\Typhoon\Typhoon;

class TypeInspectorTest extends \Ezzatron\Typhoon\Test\TestCase
{
  /**
   * @return array
   */
  public function typeOfData()
  {
    $data = array(
      array(null,     new NullType),       // #0: Null
      array(true,     new BooleanType),    // #1: Boolean
      array(false,    new BooleanType),    // #2: Boolean
      array(1,        new IntegerType),    // #3: Integer
      array(.1,       new FloatType),      // #4: Integer
      array('foo',    new StringType),     // #5: String
    );

    // #6: Resource
    $value = stream_context_create();
    $expected = new ResourceType;
    $data[] = array($value, $expected);

    // #7: Array
    $value = array();
    $expected = new ArrayType;
    $data[] = array($value, $expected);

    // #8: Traversable
    $value = Phake::mock('Iterator');
    $expected = new TraversableType;
    $expected->typhoonAttributes();
    $data[] = array($value, $expected);

    // #9: Object (stdClass)
    $value = new stdClass;
    $expected = new ObjectType;
    $expected->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'stdClass');
    $data[] = array($value, $expected);

    // #10: Object (Typhoon)
    $value = Typhoon::instance();
    $expected = new ObjectType;
    $expected->typhoonAttributes()->set(ObjectType::ATTRIBUTE_INSTANCE_OF, 'Ezzatron\Typhoon\Typhoon');
    $data[] = array($value, $expected);

    return $data;
  }

  protected function setUp()
  {
    $this->_typeInspector = new TypeInspector;
  }

  /**
   * @covers Ezzatron\Typhoon\Type\Inspector\TypeInspector::typeOf
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