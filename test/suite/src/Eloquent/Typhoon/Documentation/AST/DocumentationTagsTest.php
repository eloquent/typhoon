<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2011 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Documentation\AST;

use Eloquent\Typhoon\Primitive\String;
use Eloquent\Typhoon\Type\IntegerType;
use Eloquent\Typhoon\Type\ObjectType;

class DocumentationTagsTest extends \Eloquent\Typhoon\Test\TestCase
{
  protected function setUp()
  {
    parent::setUp();
    
    $this->_tags = new DocumentationTags;
  }

  /**
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationTags::byName
   * @group documentation
   * @group collection
   */
  public function testByName()
  {
    $tag_foo_a = new DocumentationTag(new String('foo'), new String('foo_a'));
    $tag_foo_b = new DocumentationTag(new String('foo'), new String('foo_b'));
    $tag_bar_a = new DocumentationTag(new String('bar'), new String('bar_a'));
    $tag_bar_b = new DocumentationTag(new String('bar'), new String('bar_b'));
    $this->_tags->set(NULL, $tag_foo_a);
    $this->_tags->set(NULL, $tag_foo_b);
    $this->_tags->set(NULL, $tag_bar_a);
    $this->_tags->set(NULL, $tag_bar_b);

    $expected_foo = new DocumentationTags;
    $expected_foo->set(NULL, $tag_foo_a);
    $expected_foo->set(NULL, $tag_foo_b);

    $expected_bar = new DocumentationTags;
    $expected_bar->set(NULL, $tag_bar_a);
    $expected_bar->set(NULL, $tag_bar_b);

    $expected_baz = new DocumentationTags;

    $this->assertEquals($expected_foo, $this->_tags->byName(new String('foo')));
    $this->assertEquals($expected_bar, $this->_tags->byName(new String('bar')));
    $this->assertEquals($expected_baz, $this->_tags->byName(new String('baz')));
  }

  /**
   * @return array
   */
  public function typeFailureData()
  {
    $tag = new DocumentationTag(new String('foo'), new String('bar'));

    return array(
      array('offsetSet', NULL, 'foo'),
      array('offsetSet', 'foo', $tag),
    );
  }
  
  /**
   * @covers Eloquent\Typhoon\Documentation\AST\DocumentationTags
   * @dataProvider typeFailureData
   * @group documentation
   * @group collection
   */
  public function testTypeFailure($method)
  {
    $arguments = func_get_args();
    array_shift($arguments);

    $this->setExpectedException('Eloquent\Typhoon\Assertion\Exception\UnexpectedArgumentException');
    call_user_func_array(array($this->_tags, $method), $arguments);
  }

  /**
   * @var DocumentationTags
   */
  protected $_tags;
}
