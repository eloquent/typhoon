<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\ClassMapper;

use ArrayIterator;
use Closure;
use Eloquent\Liberator\Liberator;
use FilesystemIterator;
use Phake;
use PHPUnit_Framework_TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassMapperTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_isolator = Phake::partialMock('Icecave\Isolator\Isolator');
        $this->_mapper = Phake::partialMock(__NAMESPACE__.'\ClassMapper', $this->_isolator);
    }

    protected function fileInfoFixture($path)
    {
        $fileInfo = Phake::mock('SplFileInfo');
        Phake::when($fileInfo)->getPathname()->thenReturn($path);

        return $fileInfo;
    }

    public function testClassesByDirectory()
    {
        $iterator = new ArrayIterator(array(
            $this->fileInfoFixture('foo'),
            $this->fileInfoFixture('bar'),
            $this->fileInfoFixture('baz'),
        ));
        Phake::when($this->_mapper)->fileIterator(Phake::anyParameters())->thenReturn($iterator);
        Phake::when($this->_mapper)->classesByFile(Phake::anyParameters())
            ->thenReturn(array(
                'FooOne',
                'FooTwo',
            ))
            ->thenReturn(array(
                'BarOne',
                'BarTwo',
            ))
            ->thenReturn(array(
                'BazOne',
                'BazTwo',
            ))
        ;
        $expected = array(
            'FooOne' => 'foo',
            'FooTwo' => 'foo',
            'BarOne' => 'bar',
            'BarTwo' => 'bar',
            'BazOne' => 'baz',
            'BazTwo' => 'baz',
        );

        $this->assertSame($expected, $this->_mapper->classesByDirectory('qux'));
        Phake::when($this->_mapper)->fileIterator('qux');
        Phake::when($this->_mapper)->classesByFile('foo');
        Phake::when($this->_mapper)->classesByFile('bar');
        Phake::when($this->_mapper)->classesByFile('baz');
    }

    public function testClassesByFile()
    {
        $expected = array(
            'foo',
        );
        Phake::when($this->_isolator)->file_get_contents(Phake::anyParameters())->thenReturn('bar');
        Phake::when($this->_mapper)->classesBySource(Phake::anyParameters())->thenReturn($expected);

        $this->assertSame($expected, $this->_mapper->classesByFile('baz'));
        Phake::verify($this->_isolator)->file_get_contents('baz');
        Phake::verify($this->_mapper)->classesBySource('bar');
    }

    public function classesBySourceData()
    {
        return array(
            // #0: Source with no classes
            array(
                array(
                ),
                <<<EOD
There are no classes in this source
EOD
                ,
            ),

            // #1: Source with a single class
            array(
                array(
                    'Foo',
                ),
                <<<EOD
<?php
class Foo {}
EOD
                ,
            ),

            // #1: Source with a single, namespaced class
            array(
                array(
                    'Foo\Bar\Baz',
                ),
                <<<EOD
<?php
namespace Foo\Bar;
class Baz {}
EOD
                ,
            ),

            // #1: Source with a multiple, namespaced classes, and extends/implements keywords
            array(
                array(
                    'Foo\Bar\Baz',
                    'Foo\Bar\Pip',
                ),
                <<<EOD
<?php
namespace Foo\Bar;
class Baz extends Qux implements Doom, Splat {}
class Pip extends Pop implements Pep, Pap {}
EOD
                ,
            ),
        );
    }

    /**
     * @dataProvider classesBySourceData
     */
    public function testClassesBySource(array $expected, $source)
    {
        $this->assertSame($expected, $this->_mapper->classesBySource($source));
    }

    public function testFileIterator()
    {
        $expected = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(__DIR__)
        );
        $actual = Liberator::liberate($this->_mapper)->fileIterator(__DIR__);

        $this->assertInstanceOf('RecursiveIteratorIterator', $actual);
        $this->assertSame($expected->getInnerIterator()->getPath(), $actual->getInnerIterator()->getPath());
        $this->assertSame($expected->getInnerIterator()->getFlags(), $actual->getInnerIterator()->getFlags());
    }
}
