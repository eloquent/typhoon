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
use stdClass;

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

    public function testReadWithTyphoon()
    {
        $configuration = Phake::mock(__NAMESPACE__.'\Configuration');
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn($configuration)
        ;

        $this->assertSame($configuration, $this->_reader->read('foo'));
        Phake::verify($this->_reader)->readTyphoon('foo');
        Phake::verify($this->_reader, Phake::never())
            ->readComposer(Phake::anyParameters())
        ;
    }

    public function testReadWithComposer()
    {
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn(null)
        ;
        $configuration = Phake::mock(__NAMESPACE__.'\Configuration');
        Phake::when($this->_reader)
            ->readComposer(Phake::anyParameters())
            ->thenReturn($configuration)
        ;

        $this->assertSame($configuration, $this->_reader->read('foo'));
        Phake::inOrder(
            Phake::verify($this->_reader)->readTyphoon('foo'),
            Phake::verify($this->_reader)->readComposer('foo')
        );
    }

    public function testReadWithNeither()
    {
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn(null)
        ;
        Phake::when($this->_reader)
            ->readComposer(Phake::anyParameters())
            ->thenReturn(null)
        ;

        $this->assertNull($this->_reader->read('foo'));
        Phake::inOrder(
            Phake::verify($this->_reader)->readTyphoon('foo'),
            Phake::verify($this->_reader)->readComposer('foo')
        );
    }

    public function testReadWithNeitherThrow()
    {
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn(null)
        ;
        Phake::when($this->_reader)
            ->readComposer(Phake::anyParameters())
            ->thenReturn(null)
        ;

        $this->setExpectedException(
            __NAMESPACE__.'\Exception\ConfigurationReadException'
        );
        $this->_reader->read('foo', true);
    }

    public function testReadDefaultPath()
    {
        Phake::when($this->_isolator)->getcwd()->thenReturn('bar');
        Phake::when($this->_reader)
            ->readTyphoon(Phake::anyParameters())
            ->thenReturn(null)
        ;
        Phake::when($this->_reader)
            ->readComposer(Phake::anyParameters())
            ->thenReturn(null)
        ;

        $this->assertNull($this->_reader->read());
        Phake::inOrder(
            Phake::verify($this->_reader)->readTyphoon('bar'),
            Phake::verify($this->_reader)->readComposer('bar')
        );
    }

    public function testReadTyphoon()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = new stdClass;
        $data->outputPath = 'foo';
        $data->sourcePaths = array('bar', 'baz');
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'foo',
            array('bar', 'baz')
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readTyphoon('doom')
        );
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('doom/typhoon.json'),
            Phake::verify($this->_reader)->loadJSON('doom/typhoon.json')
        );
    }

    public function testReadTyphoonAllProperties()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = new stdClass;
        $data->outputPath = 'foo';
        $data->sourcePaths = array('bar', 'baz');
        $data->loaderPaths = array('qux', 'doom');
        $data->useNativeCallable = false;
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'foo',
            array('bar', 'baz')
        );
        $expected->setLoaderPaths(array('qux', 'doom'));
        $expected->setUseNativeCallable(false);

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readTyphoon('splat')
        );
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('splat/typhoon.json'),
            Phake::verify($this->_reader)->loadJSON('splat/typhoon.json')
        );
    }

    public function testReadTyphoonNotFound()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(false)
        ;

        $this->assertNull(
            Liberator::liberate($this->_reader)->readTyphoon('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/typhoon.json');
        Phake::verify($this->_reader, Phake::never())->loadJSON('doom/typhoon.json');
    }

    public function testReadComposer()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = new stdClass;
        $data->extra = new stdClass;
        $data->extra->typhoon = new stdClass;
        $data->extra->typhoon->outputPath = 'foo';
        $data->extra->typhoon->sourcePaths = array('bar', 'baz');
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'foo',
            array('bar', 'baz')
        );

        $this->assertEquals(
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

        $this->assertNull(
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
        $data = new stdClass;
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;

        $this->assertNull(
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
        $data = new stdClass;
        $data->autoload = new stdClass;
        $data->autoload->{'psr-0'} = new stdClass;
        $data->autoload->{'psr-0'}->Foo = 'foo';
        $data->autoload->{'psr-0'}->Bar = array('bar', 'baz', 'excludeMe/foo');
        $data->autoload->{'psr-0'}->Baz = 'excludeMe';
        $data->autoload->classmap = array('qux', 'excludeMe/bar', 'doom');
        $data->autoload->files = array('excludeMe/baz', 'splat', 'ping');
        $data->{'include-path'} = array('pong', 'pang', 'excludeMe');
        $data->extra = new stdClass;
        $data->extra->typhoon = new stdClass;
        $data->extra->typhoon->outputPath = 'excludeMe';
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'excludeMe',
            array(
                'foo',
                'bar',
                'baz',
                'qux',
                'doom',
                'splat',
                'ping',
                'pong',
                'pang',
            )
        );

        $this->assertEquals(
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
        $data = new stdClass;
        $data->extra = new stdClass;
        $data->extra->typhoon = new stdClass;
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

    public function testLoadJSON()
    {
        Phake::when($this->_reader)
            ->load(Phake::anyParameters())
            ->thenReturn('{"foo": "bar", "baz": "qux"}')
        ;
        $expected = new stdClass;
        $expected->foo = 'bar';
        $expected->baz = 'qux';

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->loadJSON('doom')
        );
        Phake::verify($this->_reader)->load('doom');
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

    public function validateDataFailureData()
    {
        $data = array();

        $data['Wrong type for main entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Typhoon configuration data must be an object.",
            'foo',
        );

        $jsonData = new stdClass;
        $jsonData->sourcePaths = array('bar', 'baz');
        $jsonData->loaderPaths = array('qux', 'doom');
        $jsonData->useNativeCallable = false;
        $data['Missing outputPath'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'outputPath' is required.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = array('foo');
        $jsonData->sourcePaths = array('bar', 'baz');
        $jsonData->loaderPaths = array('qux', 'doom');
        $jsonData->useNativeCallable = false;
        $data['Wrong type for outputPath'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'outputPath' must be a string.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = 'foo';
        $jsonData->loaderPaths = array('qux', 'doom');
        $jsonData->useNativeCallable = false;
        $data['Missing sourcePaths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' is required.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = 'foo';
        $jsonData->sourcePaths = 'bar';
        $jsonData->loaderPaths = array('qux', 'doom');
        $jsonData->useNativeCallable = false;
        $data['Wrong type for sourcePaths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'sourcePaths' must be an array.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = 'foo';
        $jsonData->sourcePaths = array('bar', 111);
        $jsonData->loaderPaths = array('qux', 'doom');
        $jsonData->useNativeCallable = false;
        $data['Wrong type for sourcePaths entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Entries in 'sourcePaths' must be strings.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = 'foo';
        $jsonData->sourcePaths = array('bar', 'baz');
        $jsonData->loaderPaths = 'qux';
        $jsonData->useNativeCallable = false;
        $data['Wrong type for loaderPaths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'loaderPaths' must be an array.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = 'foo';
        $jsonData->sourcePaths = array('bar', 'baz');
        $jsonData->loaderPaths = array('qux', 111);
        $jsonData->useNativeCallable = false;
        $data['Wrong type for loaderPaths entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Entries in 'loaderPaths' must be strings.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->outputPath = 'foo';
        $jsonData->sourcePaths = array('bar', 'baz');
        $jsonData->loaderPaths = array('qux', 'doom');
        $jsonData->useNativeCallable = 'splat';
        $data['Wrong type for useNativeCallable'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. 'useNativeCallable' must be a boolean.",
            $jsonData,
        );

        return $data;
    }

    /**
     * @dataProvider validateDataFailureData
     */
    public function testValidateDataFailure($expected, $expectedMessage, $data)
    {
        $this->setExpectedException($expected, $expectedMessage);
        Liberator::liberate($this->_reader)->validateData($data);
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
