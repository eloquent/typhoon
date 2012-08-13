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
        $fooOneDefinition = new ClassDefinition('FooOne');
        $fooTwoDefinition = new ClassDefinition('FooTwo');
        $barOneDefinition = new ClassDefinition('BarOne');
        $barTwoDefinition = new ClassDefinition('BarTwo');
        $bazOneDefinition = new ClassDefinition('BazOne');
        $bazTwoDefinition = new ClassDefinition('BazTwo');
        Phake::when($this->_mapper)->classesByFile(Phake::anyParameters())
            ->thenReturn(array(
                $fooOneDefinition,
                $fooTwoDefinition,
            ))
            ->thenReturn(array(
                $barOneDefinition,
                $barTwoDefinition,
            ))
            ->thenReturn(array(
                $bazOneDefinition,
                $bazTwoDefinition,
            ))
        ;
        $expected = array(
            $fooOneDefinition,
            $fooTwoDefinition,
            $barOneDefinition,
            $barTwoDefinition,
            $bazOneDefinition,
            $bazTwoDefinition,
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
            new ClassDefinition('foo'),
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
                <<<'EOD'
There are no classes in this source
EOD
                ,
            ),

            // #1: Source with a single class
            array(
                array(
                    new ClassDefinition('Foo'),
                ),
                <<<'EOD'
<?php
class Foo {}
EOD
                ,
            ),

            // #2: Source with a single, namespaced class
            array(
                array(
                    new ClassDefinition('Baz', 'Foo\Bar'),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar;
class Baz {}
EOD
                ,
            ),

            // #3: Source with a multiple, namespaced classes, and extends/implements keywords
            array(
                array(
                    new ClassDefinition('Baz', 'Foo\Bar'),
                    new ClassDefinition('Pip', 'Foo\Bar'),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar;
class Baz extends Qux implements Doom, Splat {}
class Pip extends Pop implements Pep, Pap {}
EOD
                ,
            ),

            // #4: Source with multiple namespaces
            array(
                array(
                    new ClassDefinition('Baz', 'Foo\Bar'),
                    new ClassDefinition('Qux', 'Foo\Bar'),
                    new ClassDefinition('Pip', 'Doom\Splat'),
                    new ClassDefinition('Pop', 'Doom\Splat'),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar;
class Baz {}
class Qux {}
namespace Doom\Splat;
class Pip {}
class Pop {}
EOD
                ,
            ),

            // #5: Source with use statements
            array(
                array(
                    new ClassDefinition('Pop', 'Foo\Bar', array(
                        'Baz\Qux' => null,
                        'Doom\Splat' => 'Pip',
                    )),
                    new ClassDefinition('Pep', 'Foo\Bar', array(
                        'Baz\Qux' => null,
                        'Doom\Splat' => 'Pip',
                    )),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar;
use Baz\Qux;
use Doom\Splat as Pip;
class Pop {}
class Pep {}
EOD
                ,
            ),

            // #6: Multiple namespaces with use statements
            array(
                array(
                    new ClassDefinition('Baz', 'Foo', array(
                        'Bar' => null,
                    )),
                    new ClassDefinition('Splat', 'Qux', array(
                        'Doom' => null,
                    )),
                ),
                <<<'EOD'
<?php
namespace Foo;
use Bar;
class Baz {}
namespace Qux;
use Doom;
class Splat {}
EOD
                ,
            ),

            // #7: Ignore keywords outside of relevant context
            array(
                array(
                    new ClassDefinition('Foo'),
                    new ClassDefinition('Splat'),
                ),
                <<<'EOD'
<?php
class Foo
{
    function bar()
    {
        $baz = null;
        $qux = function() use($baz) {};
        foreach (array() as $doom) {}
    }
}
class Splat {}
EOD
                ,
            ),

            // #8: Alternate namespace syntax
            array(
                array(
                    new ClassDefinition('Baz', 'Foo\Bar'),
                    new ClassDefinition('Qux', 'Foo\Bar'),
                    new ClassDefinition('Pip', 'Doom\Splat'),
                    new ClassDefinition('Pop', 'Doom\Splat'),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar
{
    class Baz {}
    class Qux {}
}
namespace Doom\Splat
{
    class Pip {}
    class Pop {}
}
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
        $this->assertEquals($expected, $this->_mapper->classesBySource($source));
    }

    public function testClassBySource()
    {
        $fooDefinition = new ClassDefinition('Foo');
        $barDefinition = new ClassDefinition('Bar');
        Phake::when($this->_mapper)
            ->classesBySource(Phake::anyParameters())
            ->thenReturn(array(
                $fooDefinition,
                $barDefinition,
            ))
        ;

        $this->assertSame($fooDefinition, $this->_mapper->classBySource('Foo', 'baz'));
        $this->assertSame($barDefinition, $this->_mapper->classBySource('Bar', 'qux'));
        Phake::inOrder(
            Phake::verify($this->_mapper)->classesBySource('baz'),
            Phake::verify($this->_mapper)->classesBySource('qux')
        );
    }

    public function testClassBySourceFailure()
    {
        Phake::when($this->_mapper)
            ->classesBySource(Phake::anyParameters())
            ->thenReturn(array(
                new ClassDefinition('Foo'),
            ))
        ;

        $this->setExpectedException(__NAMESPACE__.'\Exception\UndefinedClassException');
        $this->_mapper->classBySource('Bar', 'qux');
    }

    public function testFileIterator()
    {
        $expected = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                __DIR__,
                FilesystemIterator::FOLLOW_SYMLINKS |
                FilesystemIterator::SKIP_DOTS
            )
        );
        $actual = Liberator::liberate($this->_mapper)->fileIterator(__DIR__);

        $this->assertInstanceOf('RecursiveIteratorIterator', $actual);
        $this->assertSame($expected->getInnerIterator()->getPath(), $actual->getInnerIterator()->getPath());
        $this->assertSame($expected->getInnerIterator()->getFlags(), $actual->getInnerIterator()->getFlags());
    }
}
