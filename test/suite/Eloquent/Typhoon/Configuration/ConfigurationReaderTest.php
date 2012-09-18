<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Eloquent\Liberator\Liberator;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use ErrorException;
use Phake;

class ConfigurationReaderTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_reader = Phake::partialMock(
            __NAMESPACE__.'\ConfigurationReader',
            $this->_isolator
        );
    }

    public function testRead()
    {
        $composerData = array(
            'outputPath' => 'foo',
            'loaderPaths' => array('qux', 'doom'),
        );
        Phake::when($this->_reader)
            ->readComposer(Phake::anyParameters())
            ->thenReturn($composerData)
        ;
        $typhoonData = array(
            'sourcePaths' => array('bar', 'baz'),
            'useNativeCallable' => false,
        );
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn($typhoonData)
        ;
        $mergedData = array(
            'outputPath' => 'foo',
            'loaderPaths' => array('qux', 'doom'),
            'sourcePaths' => array('bar', 'baz'),
            'useNativeCallable' => false,
        );
        $expected = new Configuration(
            'foo',
            array('bar', 'baz'),
            array('qux', 'doom'),
            false
        );

        $this->assertEquals($expected, $this->_reader->read('splat'));
        Phake::inOrder(
            Phake::verify($this->_reader)->readComposer('splat'),
            Phake::verify($this->_reader)->readTyphoon('splat'),
            Phake::verify($this->_reader)->finalizeData($mergedData)
        );
    }

    public function testReadDefaultPath()
    {
        Phake::when($this->_isolator)->getcwd()->thenReturn('ping');
        $composerData = array(
            'sourcePaths' => array('bar', 'baz'),
            'useNativeCallable' => false,
        );
        Phake::when($this->_reader)
            ->readComposer(Phake::anyParameters())
            ->thenReturn($composerData)
        ;
        $typhoonData = array(
            'outputPath' => 'foo',
            'loaderPaths' => array('qux', 'doom'),
        );
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn($typhoonData)
        ;
        $mergedData = array(
            'sourcePaths' => array('bar', 'baz'),
            'useNativeCallable' => false,
            'outputPath' => 'foo',
            'loaderPaths' => array('qux', 'doom'),
        );
        $expected = new Configuration(
            'foo',
            array('bar', 'baz'),
            array('qux', 'doom'),
            false
        );

        $this->assertEquals($expected, $this->_reader->read());
        Phake::inOrder(
            Phake::verify($this->_reader)->readComposer('ping'),
            Phake::verify($this->_reader)->readTyphoon('ping'),
            Phake::verify($this->_reader)->finalizeData($mergedData)
        );
    }

    public function testReadComposer()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = array(
            'extra' => array(
                'typhoon' => array(
                    'outputPath' => 'foo',
                    'sourcePaths' => array('bar', 'baz'),
                ),
            ),
        );
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = $data['extra']['typhoon'];

        $this->assertSame(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('doom/composer.json'),
            Phake::verify($this->_reader)->loadJSON('doom/composer.json')
        );
    }

    public function testReadComposerNotFound()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(false)
        ;

        $this->assertSame(
            array(),
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
        Phake::verify($this->_reader, Phake::never())->loadJSON('doom/composer.json');
    }

    public function testReadComposerNoTyphoonData()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = array();
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;

        $this->assertSame(
            array(),
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('doom/composer.json'),
            Phake::verify($this->_reader)->loadJSON('doom/composer.json')
        );
    }

    public function testReadComposerParseAutoload()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = array(
            'autoload' => array(
                'psr-0' => array(
                    'Foo' => 'foo',
                    'Bar' => array('bar', 'baz', 'excludeMe/foo'),
                    'Baz' => 'excludeMe',
                ),
                'classmap' => array('qux', 'excludeMe/bar', 'doom'),
                'files' => array('excludeMe/baz', 'splat', 'ping'),
            ),
            'include-path' => array('pong', 'pang', 'excludeMe'),
            'extra' => array(
                'typhoon' => array(
                    'outputPath' => 'excludeMe',
                ),
            ),
        );
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = array(
            'outputPath' => 'excludeMe',
            'sourcePaths' => array(
                'foo',
                'bar',
                'baz',
                'qux',
                'doom',
                'splat',
                'ping',
                'pong',
                'pang',
            ),
        );

        $this->assertSame(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('doom/composer.json'),
            Phake::verify($this->_reader)->loadJSON('doom/composer.json')
        );
    }

    public function testReadComposerFailureNoOutputPath()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = array(
            'extra' => array(
                'typhoon' => array(),
            ),
        );
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'outputPath' is required."
        );
        Liberator::liberate($this->_reader)->readComposer('doom');
    }

    public function testReadTyphoon()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = array('foo' => 'bar', 'baz' => 'qux');
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;

        $this->assertSame(
            $data,
            Liberator::liberate($this->_reader)->readTyphoon('doom')
        );
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('doom/typhoon.json'),
            Phake::verify($this->_reader)->loadJSON('doom/typhoon.json')
        );
    }

    public function testReadTyphoonNotFound()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(false)
        ;

        $this->assertSame(
            array(),
            Liberator::liberate($this->_reader)->readTyphoon('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/typhoon.json');
        Phake::verify($this->_reader, Phake::never())->loadJSON('doom/typhoon.json');
    }

    public function testLoadJSON()
    {
        Phake::when($this->_reader)
            ->load(Phake::anyParameters())
            ->thenReturn('{"foo": "bar", "baz": "qux"}')
        ;

        $this->assertSame(
            array('foo' => 'bar', 'baz' => 'qux'),
            Liberator::liberate($this->_reader)->loadJSON('bar')
        );
        Phake::verify($this->_reader)->load('bar');
    }

    public function testLoadJSONFailureInvalidJSON()
    {
        Phake::when($this->_reader)
            ->load(Phake::anyParameters())
            ->thenReturn('{')
        ;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\InvalidJSONException',
            "Invalid JSON in 'bar' - Syntax error."
        );
        Liberator::liberate($this->_reader)->loadJSON('bar');
    }

    public function testLoad()
    {
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn('foo')
        ;

        $this->assertSame(
            'foo',
            Liberator::liberate($this->_reader)->load('bar')
        );
        Phake::verify($this->_isolator)->file_get_contents('bar');
    }

    public function testLoadFailure()
    {
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenThrow(new ErrorException)
        ;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\ConfigurationReadException'
        );
        Liberator::liberate($this->_reader)->load('bar');
    }

    public function testFinalizeDataDefaults()
    {
        $data = array(
            'outputPath' => 'foo',
            'sourcePaths' => array('bar', 'baz'),
        );
        $expected = array(
            'outputPath' => 'foo',
            'sourcePaths' => array('bar', 'baz'),
            'loaderPaths' => array('vendor/autoload.php'),
            'useNativeCallable' => true,
        );

        $this->assertSame(
            $expected,
            Liberator::liberate($this->_reader)->finalizeData($data)
        );
    }

    public function finalizeDataFailureData()
    {
        $data = array();

        $data['Wrong type for main entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid type for Typhoon configuration data.",
            'foo',
        );

        $data['Missing outputPath'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'outputPath' is required.",
            array(
                'sourcePaths' => array('bar', 'baz'),
                'loaderPaths' => array('qux', 'doom'),
                'useNativeCallable' => false,
            ),
        );

        $data['Wrong type for outputPath'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'outputPath' must be a string.",
            array(
                'outputPath' => array('foo'),
                'sourcePaths' => array('bar', 'baz'),
                'loaderPaths' => array('qux', 'doom'),
                'useNativeCallable' => false,
            ),
        );

        $data['Missing sourcePaths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' is required.",
            array(
                'outputPath' => 'foo',
                'loaderPaths' => array('qux', 'doom'),
                'useNativeCallable' => false,
            ),
        );

        $data['Wrong type for sourcePaths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' must be an array.",
            array(
                'outputPath' => 'foo',
                'sourcePaths' => 'bar',
                'loaderPaths' => array('qux', 'doom'),
                'useNativeCallable' => false,
            ),
        );

        $data['Wrong type for sourcePaths entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Entries in 'sourcePaths' must be strings.",
            array(
                'outputPath' => 'foo',
                'sourcePaths' => array('bar', 111),
                'loaderPaths' => array('qux', 'doom'),
                'useNativeCallable' => false,
            ),
        );

        $data['Wrong type for loaderPaths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'loaderPaths' must be an array.",
            array(
                'outputPath' => 'foo',
                'sourcePaths' => array('bar', 'baz'),
                'loaderPaths' => 'qux',
                'useNativeCallable' => false,
            ),
        );

        $data['Wrong type for loaderPaths entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Entries in 'loaderPaths' must be strings.",
            array(
                'outputPath' => 'foo',
                'sourcePaths' => array('bar', 'baz'),
                'loaderPaths' => array('qux', 111),
                'useNativeCallable' => false,
            ),
        );

        $data['Wrong type for useNativeCallable'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'useNativeCallable' must be a boolean.",
            array(
                'outputPath' => 'foo',
                'sourcePaths' => array('bar', 'baz'),
                'loaderPaths' => array('qux', 'doom'),
                'useNativeCallable' => 'foo',
            ),
        );

        return $data;
    }

    /**
     * @dataProvider finalizeDataFailureData
     */
    public function testFinalizeDataFailure($expected, $expectedMessage, $data)
    {
        $this->setExpectedException($expected, $expectedMessage);
        Liberator::liberate($this->_reader)->finalizeData($data);
    }

    public function pathIsDescandantOrEqualData()
    {
        $data = array();

        $expected = true;
        $workingPath = '/foo';
        $ancestor = 'bar';
        $descendant = 'bar';
        $data['Path is equal relative'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = 'bar';
        $descendant = 'bar/baz';
        $data['Path is descendant relative'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = './bar/.';
        $descendant = 'bar/./baz';
        $data['Path is descendant relative with dots'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = false;
        $workingPath = '/foo';
        $ancestor = 'bar';
        $descendant = 'baz';
        $data['Path is not descendant relative'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = '/bar';
        $descendant = '/bar';
        $data['Path is equal absolute'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = '/bar';
        $descendant = '/bar/baz';
        $data['Path is descendant absolute'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = '/./bar';
        $descendant = '/bar/./baz';
        $data['Path is descendant absolute with dots'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = false;
        $workingPath = '/foo';
        $ancestor = '/bar';
        $descendant = '/baz';
        $data['Path is not descendant absolute'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = 'bar';
        $descendant = '/foo/bar';
        $data['Path is equal mixed relative and absolute'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = 'bar';
        $descendant = '/foo/bar/baz';
        $data['Path is descendant mixed relative and absolute'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = true;
        $workingPath = '/foo';
        $ancestor = './bar';
        $descendant = '/foo/./bar/./baz/.';
        $data['Path is descendant mixed relative and absolute with dots'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        $expected = false;
        $workingPath = '/foo';
        $ancestor = 'bar';
        $descendant = '/baz';
        $data['Path is not descendant mixed relative and absolute'] = array(
            $expected,
            $workingPath,
            $ancestor,
            $descendant,
        );

        return $data;
    }

    /**
     * @dataProvider pathIsDescandantOrEqualData
     */
    public function testPathIsDescandantOrEqual(
        $expected,
        $workingPath,
        $ancestor,
        $descendant
    ) {
        $this->assertSame(
            $expected,
            Liberator::liberate($this->_reader)->pathIsDescandantOrEqual(
                $workingPath,
                $ancestor,
                $descendant
            )
        );
    }
}
