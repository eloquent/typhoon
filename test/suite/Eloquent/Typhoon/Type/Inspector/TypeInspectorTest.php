<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Type\Inspector;

use Phake;
use stdClass;
use Eloquent\Typhoon\Primitive\Integer;
use Eloquent\Typhoon\Type\ArrayType;
use Eloquent\Typhoon\Type\BooleanType;
use Eloquent\Typhoon\Type\Composite\OrType;
use Eloquent\Typhoon\Type\DirectoryType;
use Eloquent\Typhoon\Type\FileType;
use Eloquent\Typhoon\Type\FloatType;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\NullType;
use Eloquent\Typhoon\Type\ObjectType;
use Eloquent\Typhoon\Type\SocketType;
use Eloquent\Typhoon\Type\StreamType;
use Eloquent\Typhoon\Type\StringType;
use Eloquent\Typhoon\Type\ResourceType;
use Eloquent\Typhoon\Type\TraversableType;
use Eloquent\Typhoon\Typhoon;

class TypeInspectorTest extends \Eloquent\Typhoon\Test\TestCase
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
      array(.1,       new FloatType),      // #4: Float
      array('foo',    new StringType),     // #5: String
    );

    // #6: File
    $value = $this->fileFixture();
    $expected = new FileType;
    $data[] = array($value, $expected);

    // #7: Directory
    $value = $this->directoryFixture();
    $expected = new DirectoryType;
    $data[] = array($value, $expected);

    // #8: Stream
    $value = $this->streamFixture();
    $expected = new StreamType;
    $data[] = array($value, $expected);

    // #9: Resource
    $value = $this->resourceFixture();
    $expected = new ResourceType;
    $data[] = array($value, $expected);

    // #10: Array
    $value = array();
    $expected = new ArrayType;
    $data[] = array($value, $expected);

    // #11: Traversable
    $value = Phake::mock('Iterator');
    $expected = new TraversableType(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => get_class($value),
    ));
    $data[] = array($value, $expected);

    // #12: Object (stdClass)
    $value = new stdClass;
    $expected = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => get_class($value),
    ));
    $data[] = array($value, $expected);

    // #13: Object (Typhoon)
    $value = Typhoon::instance();
    $expected = new ObjectType(array(
      ObjectType::ATTRIBUTE_INSTANCE_OF => get_class($value),
    ));
    $data[] = array($value, $expected);

    // #14: Array with sub-values and 0-depth checking
    $value = array(
      'foo',
      'bar' => true,
    );
    $expected = new ArrayType;
    $data[] = array($value, $expected, new Integer(0));
    
    if (extension_loaded('sockets'))
    {
      // #15: Socket
      $value = $this->socketFixture();
      $expected = new SocketType;
      $data[] = array($value, $expected);
    }

    return $data;
  }

  /**
   * @return array
   */
  public function typeOfTraversableData()
  {
    $data = array();
    
    // #0: Single integer key and string sub-value
    $values = array(
      'foo',
    );
    $expectedKeyType = new IntegerType;
    $expectedSubType = new StringType;
    $data[] = array($values, $expectedKeyType, $expectedSubType, new Integer(1));

    // #1: Multiple integer keys and string sub-values
    $values = array(
      'foo',
      'bar',
    );
    $expectedKeyType = new IntegerType;
    $expectedSubType = new StringType;
    $data[] = array($values, $expectedKeyType, $expectedSubType, new Integer(1));

    // #2: Mixed keys and sub-values
    $values = array(
      'foo',
      'bar' => true,
    );
    $expectedKeyType = new OrType;
    $expectedKeyType->addTyphoonType(new IntegerType);
    $expectedKeyType->addTyphoonType(new StringType);
    $expectedSubType = new OrType;
    $expectedSubType->addTyphoonType(new StringType);
    $expectedSubType->addTyphoonType(new BooleanType);
    $data[] = array($values, $expectedKeyType, $expectedSubType, new Integer(1));

    // #3: Recursive sub-value checking, 1-depth
    $values = array(
      'foo' => array(
        'bar',
        'baz' => true,
      ),
    );
    $expectedKeyType = new StringType;
    $expectedSubType = new ArrayType;
    $data[] = array($values, $expectedKeyType, $expectedSubType, new Integer(1));

    // #4: Recursive sub-value checking, 2-depth
    $values = array(
      'foo' => array(
        'bar',
        'baz' => true,
      ),
    );
    $expectedKeyType = new StringType;
    $expectedSubKeyType = new OrType;
    $expectedSubKeyType->addTyphoonType(new IntegerType);
    $expectedSubKeyType->addTyphoonType(new StringType);
    $expectedSubSubType = new OrType;
    $expectedSubSubType->addTyphoonType(new StringType);
    $expectedSubSubType->addTyphoonType(new BooleanType);
    $expectedSubType = new ArrayType;
    $expectedSubType->setTyphoonKeyType($expectedSubKeyType);
    $expectedSubType->setTyphoonSubType($expectedSubSubType);
    $data[] = array($values, $expectedKeyType, $expectedSubType, new Integer(2));

    return $data;
  }

  protected function setUp()
  {
    $this->_typeInspector = new TypeInspector;
  }

  /**
   * @covers Eloquent\Typhoon\Type\Inspector\TypeInspector
   * @dataProvider typeOfData
   * @group type
   * @group type-inspector
   * @group core
   */
  public function testTypeOf($value, $expected, Integer $depth = null)
  {
    $this->assertEquals($expected, $this->_typeInspector->typeOf($value, $depth));
  }

  /**
   * @covers Eloquent\Typhoon\Type\Inspector\TypeInspector
   * @dataProvider typeOfTraversableData
   * @group type
   * @group type-inspector
   * @group core
   */
  public function testTypeOfTraversable(array $values, $expectedKeyType, $expectedSubType, Integer $depth = null)
  {
    $expected = new ArrayType;
    $expected->setTyphoonKeyType($expectedKeyType);
    $expected->setTyphoonSubType($expectedSubType);

    $this->assertEquals($expected, $this->_typeInspector->typeOf($values, $depth));
    
    $traversable = $this->traversableFixture($values);
    $expected = new TraversableType(array(
      TraversableType::ATTRIBUTE_INSTANCE_OF => get_class($traversable),
    ));
    $expected->setTyphoonKeyType($expectedKeyType);
    $expected->setTyphoonSubType($expectedSubType);
    
    $this->assertEquals($expected, $this->_typeInspector->typeOf($traversable, $depth));
  }

  /**
   * @var TypeInspector
   */
  protected $_typeInspector;
}
