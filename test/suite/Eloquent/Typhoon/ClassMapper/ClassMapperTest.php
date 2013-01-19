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
use Eloquent\Cosmos\ClassName;
use Eloquent\Liberator\Liberator;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use FilesystemIterator;
use Icecave\Pasta\AST\Type\AccessModifier;
use Phake;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassMapperTest extends MultiGenerationTestCase
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

    public function testClassesByPathDirectory()
    {
        $classDefinitionA = new ClassDefinition(ClassName::fromString('\A'));
        $classDefinitionB = new ClassDefinition(ClassName::fromString('\B'));
        $classDefinitions = array(
            $classDefinitionA,
            $classDefinitionB,
        );
        Phake::when($this->_isolator)->is_dir(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->_mapper)
            ->classesByDirectory(Phake::anyParameters())
            ->thenReturn($classDefinitions)
        ;

        $this->assertSame($classDefinitions, $this->_mapper->classesByPath('foo'));
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_dir('foo'),
            Phake::verify($this->_mapper)->classesByDirectory('foo')
        );
    }

    public function testClassesByPathFile()
    {
        $classDefinitionA = new ClassDefinition(ClassName::fromString('\A'));
        $classDefinitionB = new ClassDefinition(ClassName::fromString('\B'));
        $classDefinitions = array(
            $classDefinitionA,
            $classDefinitionB,
        );
        Phake::when($this->_isolator)->is_dir(Phake::anyParameters())->thenReturn(false);
        Phake::when($this->_mapper)
            ->classesByFile(Phake::anyParameters())
            ->thenReturn($classDefinitions)
        ;

        $this->assertSame($classDefinitions, $this->_mapper->classesByPath('foo'));
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_dir('foo'),
            Phake::verify($this->_mapper)->classesByFile('foo')
        );
    }

    public function testClassesByDirectory()
    {
        $iterator = new ArrayIterator(array(
            $this->fileInfoFixture('foo'),
            $this->fileInfoFixture('bar'),
            $this->fileInfoFixture('baz'),
        ));
        Phake::when($this->_mapper)->fileIterator(Phake::anyParameters())->thenReturn($iterator);
        $fooOneDefinition = new ClassDefinition(ClassName::fromString('\FooOne'));
        $fooTwoDefinition = new ClassDefinition(ClassName::fromString('\FooTwo'));
        $barOneDefinition = new ClassDefinition(ClassName::fromString('\BarOne'));
        $barTwoDefinition = new ClassDefinition(ClassName::fromString('\BarTwo'));
        $bazOneDefinition = new ClassDefinition(ClassName::fromString('\BazOne'));
        $bazTwoDefinition = new ClassDefinition(ClassName::fromString('\BazTwo'));
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
            new ClassDefinition(ClassName::fromString('\foo')),
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
            'Source with no classes' => array(
                array(
                ),
                <<<'EOD'
There are no classes in this source
EOD
                ,
            ),

            'Source with a single class' => array(
                array(
                    new ClassDefinition(ClassName::fromString('\Foo')),
                ),
                <<<'EOD'
<?php
class Foo {}
EOD
                ,
            ),

            'Source with a single, namespaced class' => array(
                array(
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Baz')),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar;
class Baz {}
EOD
                ,
            ),

            'Source with a multiple, namespaced classes, and extends/implements keywords' => array(
                array(
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Baz')),
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Pip')),
                ),
                <<<'EOD'
<?php
namespace Foo\Bar;
class Baz extends Qux implements Doom, Splat {}
class Pip extends Pop implements Pep, Pap {}
EOD
                ,
            ),

            'Source with multiple namespaces' => array(
                array(
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Baz')),
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Qux')),
                    new ClassDefinition(ClassName::fromString('\Doom\Splat\Pip')),
                    new ClassDefinition(ClassName::fromString('\Doom\Splat\Pop')),
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

            'Source with use statements' => array(
                array(
                    new ClassDefinition(
                        ClassName::fromString('\Foo\Bar\Pop'),
                        array(
                            array(
                                ClassName::fromString('Baz\Qux'),
                            ),
                            array(
                                ClassName::fromString('Doom\Splat'),
                                ClassName::fromString('Pip')
                            )
                        )
                    ),
                    new ClassDefinition(
                        ClassName::fromString('\Foo\Bar\Pep'),
                        array(
                            array(
                                ClassName::fromString('Baz\Qux'),
                            ),
                            array(
                                ClassName::fromString('Doom\Splat'),
                                ClassName::fromString('Pip')
                            )
                        )
                    ),
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

            'Multiple namespaces with use statements' => array(
                array(
                    new ClassDefinition(
                        ClassName::fromString('\Foo\Baz'),
                        array(
                            array(
                                ClassName::fromString('Bar'),
                            )
                        )
                    ),
                    new ClassDefinition(
                        ClassName::fromString('\Qux\Splat'),
                        array(
                            array(
                                ClassName::fromString('Doom'),
                            )
                        )
                    ),
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

            'Ignore keywords outside of relevant context' => array(
                array(
                    new ClassDefinition(
                        ClassName::fromString('Foo'),
                        array(),
                        array(
                            new MethodDefinition(
                                'bar',
                                false,
                                AccessModifier::PUBLIC_(),
                                4,
                                "public function bar()\n    {\n        \$baz = null;\n        \$qux = function() use (\$baz) {};\n        foreach (array() as \$doom) {}\n    }"
                            ),
                        )
                    ),
                    new ClassDefinition(ClassName::fromString('\Splat')),
                ),
                <<<'EOD'
<?php
class Foo
{
    public function bar()
    {
        $baz = null;
        $qux = function() use ($baz) {};
        foreach (array() as $doom) {}
    }
}
class Splat {}
EOD
                ,
            ),

            'Alternate namespace syntax' => array(
                array(
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Baz')),
                    new ClassDefinition(ClassName::fromString('\Foo\Bar\Qux')),
                    new ClassDefinition(ClassName::fromString('\Doom\Splat\Pip')),
                    new ClassDefinition(ClassName::fromString('\Doom\Splat\Pop')),
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

            'Source with properties' => array(
                array(
                    new ClassDefinition(
                        ClassName::fromString('Foo'),
                        array(),
                        array(),
                        array(
                            new PropertyDefinition(
                                'bar',
                                false,
                                AccessModifier::PUBLIC_(),
                                4,
                                'public $bar;'
                            ),
                            new PropertyDefinition(
                                'baz',
                                true,
                                AccessModifier::PROTECTED_(),
                                5,
                                'protected static $baz;'
                            ),
                            new PropertyDefinition(
                                'qux',
                                false,
                                AccessModifier::PRIVATE_(),
                                6,
                                'private $qux;'
                            ),
                        )
                    ),
                    new ClassDefinition(
                        ClassName::fromString('Bar'),
                        array(),
                        array(),
                        array(
                            new PropertyDefinition(
                                'doom',
                                false,
                                AccessModifier::PUBLIC_(),
                                10,
                                "public \$doom = <<<EOT\npeng pung\npip pop\nEOT;"
                            ),
                            new PropertyDefinition(
                                'splat',
                                true,
                                AccessModifier::PROTECTED_(),
                                14,
                                "static protected \$splat\n    ;"
                            ),
                            new PropertyDefinition(
                                'ping',
                                false,
                                AccessModifier::PRIVATE_(),
                                16,
                                "private \$ping = array('pang', 'pong');"
                            ),
                        )
                    ),
                ),
                <<<'EOD'
<?php
class Foo
{
    public $bar;
    protected static $baz;
    private $qux;
}
class Bar
{
    public $doom = <<<EOT
peng pung
pip pop
EOT;
    static protected $splat
    ;
    private $ping = array('pang', 'pong');
}
EOD
                ,
            ),

            'Source with methods' => array(
                array(
                    new ClassDefinition(
                        ClassName::fromString('Foo'),
                        array(),
                        array(
                            new MethodDefinition(
                                '__construct',
                                false,
                                AccessModifier::PUBLIC_(),
                                4,
                                "public function __construct()\n    {\n        // baz\n    }"
                            ),
                            new MethodDefinition(
                                'qux',
                                true,
                                AccessModifier::PROTECTED_(),
                                9,
                                "protected static function qux()\n    {\n        // doom\n    }"
                            ),
                        )
                    ),
                    new ClassDefinition(
                        ClassName::fromString('Bar'),
                        array(),
                        array(
                            new MethodDefinition(
                                'splat',
                                false,
                                AccessModifier::PRIVATE_(),
                                16,
                                "private function splat(array \$ping = array())\n    {\n        \$pong = function() use(\$ping) {\n        };\n    }"
                            ),
                            new MethodDefinition(
                                'pang',
                                true,
                                AccessModifier::PUBLIC_(),
                                22,
                                "static public function pang()\n    {\n        // pung\n    }"
                            ),
                        )
                    ),
                ),
                <<<'EOD'
<?php
class Foo
{
    public function __construct()
    {
        // baz
    }

    protected static function qux()
    {
        // doom
    }
}
class Bar
{
    private function splat(array $ping = array())
    {
        $pong = function() use($ping) {
        };
    }

    static public function pang()
    {
        // pung
    }
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
        $fooDefinition = new ClassDefinition(ClassName::fromString('\Foo'));
        $barDefinition = new ClassDefinition(ClassName::fromString('\Bar'));
        Phake::when($this->_mapper)
            ->classesBySource(Phake::anyParameters())
            ->thenReturn(array(
                $fooDefinition,
                $barDefinition,
            ))
        ;

        $this->assertSame($fooDefinition, $this->_mapper->classBySource(ClassName::fromString('Foo'), 'baz'));
        $this->assertSame($barDefinition, $this->_mapper->classBySource(ClassName::fromString('Bar'), 'qux'));
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
                new ClassDefinition(ClassName::fromString('\Foo')),
            ))
        ;

        $this->setExpectedException(__NAMESPACE__.'\Exception\UndefinedClassException');
        $this->_mapper->classBySource(ClassName::fromString('Bar'), 'qux');
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
