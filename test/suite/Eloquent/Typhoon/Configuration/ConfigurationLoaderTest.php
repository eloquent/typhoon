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
use LogicException;
use Phake;
use stdClass;

class ConfigurationLoaderTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_validator = Phake::partialMock(__NAMESPACE__.'\ConfigurationValidator');
        $this->_isolator = Phake::partialMock('Icecave\Isolator\Isolator');
        $this->_loader = Phake::partialMock(
            __NAMESPACE__.'\ConfigurationLoader',
            $this->_validator,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_validator, $this->_loader->validator());
    }

    public function testConstructorDefaults()
    {
        $loader = new ConfigurationLoader;

        $this->assertInstanceOf(
            __NAMESPACE__.'\ConfigurationValidator',
            $loader->validator()
        );
    }

    public function testLoadWithStandalone()
    {
        $configuration = Phake::mock(__NAMESPACE__.'\Configuration');
        Phake::when($this->_isolator)->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_loader)
            ->loadStandalone(Phake::anyParameters())
            ->thenReturn(
                $configuration
            )
        ;

        $this->assertSame($configuration, $this->_loader->load('foo'));
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('foo/typhoon.json'),
            Phake::verify($this->_loader)->loadStandalone(
                'foo/typhoon.json',
                'foo'
            )
        );
        Phake::verify($this->_loader, Phake::never())
            ->loadComposer(Phake::anyParameters())
        ;
    }

    public function testLoadWithComposer()
    {
        $configuration = Phake::mock(__NAMESPACE__.'\Configuration');
        Phake::when($this->_isolator)->is_file(Phake::anyParameters())
            ->thenReturn(false)
            ->thenReturn(true)
        ;
        Phake::when($this->_loader)
            ->loadComposer(Phake::anyParameters())
            ->thenReturn(
                $configuration
            )
        ;

        $this->assertSame($configuration, $this->_loader->load('foo'));
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('foo/typhoon.json'),
            Phake::verify($this->_isolator)->is_file('foo/composer.json'),
            Phake::verify($this->_loader)->loadComposer(
                'foo/composer.json',
                'foo'
            )
        );
        Phake::verify($this->_loader, Phake::never())
            ->loadStandalone(Phake::anyParameters())
        ;
    }

    public function testLoadWithNeither()
    {
        Phake::when($this->_isolator)->is_file(Phake::anyParameters())
            ->thenReturn(false)
            ->thenReturn(false)
        ;

        $this->assertNull($this->_loader->load('foo'));
        Phake::inOrder(
            Phake::verify($this->_isolator)->is_file('foo/typhoon.json'),
            Phake::verify($this->_isolator)->is_file('foo/composer.json')
        );
        Phake::verify($this->_loader, Phake::never())
            ->loadStandalone(Phake::anyParameters())
        ;
        Phake::verify($this->_loader, Phake::never())
            ->loadComposer(Phake::anyParameters())
        ;
    }

    public function testLoadStandalone()
    {
        $jsonData = Phake::mock('stdClass');
        Phake::when($this->_loader)
            ->loadJSONFile(Phake::anyParameters())
            ->thenReturn($jsonData)
        ;
        $configuration = Phake::mock(__NAMESPACE__.'\Configuration');
        Phake::when($this->_loader)
            ->build(Phake::anyParameters())
            ->thenReturn($configuration)
        ;

        $this->assertSame(
            $configuration,
            $this->_loader->loadStandalone('foo', 'bar')
        );
        Phake::inOrder(
            Phake::verify($this->_loader)->loadJSONFile('foo'),
            Phake::verify($this->_loader)->build(
                $this->identicalTo($jsonData),
                'bar'
            )
        );
    }

    public function testLoadComposer()
    {
        $typhoonData = Phake::mock('stdClass');
        $composerData = new stdClass;
        $composerData->extra = new stdClass;
        $composerData->extra->typhoon = $typhoonData;
        Phake::when($this->_loader)
            ->loadJSONFile(Phake::anyParameters())
            ->thenReturn($composerData)
        ;
        $configuration = Phake::mock(__NAMESPACE__.'\Configuration');
        Phake::when($this->_loader)
            ->build(Phake::anyParameters())
            ->thenReturn($configuration)
        ;

        $this->assertSame(
            $configuration,
            $this->_loader->loadComposer('foo', 'bar')
        );
        Phake::inOrder(
            Phake::verify($this->_loader)->loadJSONFile('foo'),
            Phake::verify($this->_loader)->build(
                $this->identicalTo($typhoonData),
                'bar'
            )
        );
    }

    public function testLoadComposerNoTyphoonConfig()
    {
        $composerData = new stdClass;
        $composerData->extra = new stdClass;
        Phake::when($this->_loader)
            ->loadJSONFile(Phake::anyParameters())
            ->thenReturn($composerData)
        ;

        $this->assertNull($this->_loader->loadComposer('foo', 'bar'));
        Phake::verify($this->_loader)->loadJSONFile('foo');
        Phake::verify($this->_loader, Phake::never())
            ->build(Phake::anyParameters())
        ;
    }

    public function testLoadJSONFile()
    {
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn('{"foo": "bar", "baz": "qux"}')
        ;
        $expected = new stdClass;
        $expected->foo = 'bar';
        $expected->baz = 'qux';

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_loader)->loadJSONFile('doom')
        );
    }

    public function testLoadJSONFileFileSystemFailure()
    {
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenThrow(new ErrorException)
        ;

        $this->setExpectedException(__NAMESPACE__.'\Exception\ConfigurationReadException');
        Liberator::liberate($this->_loader)->loadJSONFile('foo');
    }

    public function testLoadJSONFileInvalidJSONFailure()
    {
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn('{')
        ;

        $this->setExpectedException(__NAMESPACE__.'\Exception\ConfigurationReadException');
        Liberator::liberate($this->_loader)->loadJSONFile('foo');
    }

    public function testBuild()
    {
        Phake::when($this->_isolator)->getcwd(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        $data = new stdClass;
        $data->outputPath = 'bar';
        $data->sourcePaths = array('baz', 'qux');
        $expected = new Configuration(
            'bar',
            array('baz', 'qux'),
            array('foo/vendor/autoload.php'),
            true
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_loader)->build($data)
        );
        Phake::verify($this->_validator)->validate(
            $this->identicalTo($data)
        );
    }

    public function testBuildWithAllOptions()
    {
        Phake::when($this->_isolator)->getcwd(Phake::anyParameters())
            ->thenReturn('foo')
        ;
        $data = new stdClass;
        $data->outputPath = 'bar';
        $data->sourcePaths = array('baz', 'qux');
        $data->loaderPaths = array('doom', 'splat');
        $data->useNativeCallable = false;
        $expected = new Configuration(
            'bar',
            array('baz', 'qux'),
            array('doom', 'splat'),
            false
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_loader)->build($data)
        );
        Phake::verify($this->_validator)->validate(
            $this->identicalTo($data)
        );
    }
}
