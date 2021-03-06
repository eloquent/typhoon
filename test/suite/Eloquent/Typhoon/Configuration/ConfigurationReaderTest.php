<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\Configuration;

use Eloquent\Composer\Configuration\ConfigurationReader as ComposerReader;
use Eloquent\Cosmos\ClassName;
use Eloquent\Liberator\Liberator;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use ErrorException;
use Icecave\Isolator\Isolator;
use Phake;
use stdClass;
use Symfony\Component\Filesystem\Filesystem;

class ConfigurationReaderTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_isolator = Phake::mock('Icecave\Isolator\Isolator');
        $this->_filesystemHelper = new Filesystem;
        $this->_composerReader = new ComposerReader(null, $this->_isolator);
        $this->_reader = Phake::partialMock(
            __NAMESPACE__.'\ConfigurationReader',
            $this->_filesystemHelper,
            $this->_composerReader,
            $this->_isolator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_filesystemHelper, $this->_reader->filesystemHelper());
        $this->assertSame($this->_composerReader, $this->_reader->composerReader());
    }

    public function testConstructorDefaults()
    {
        $this->_reader = new ConfigurationReader;

        $this->assertInstanceOf(
            'Symfony\Component\Filesystem\Filesystem',
            $this->_reader->filesystemHelper()
        );
        $this->assertInstanceOf(
            'Eloquent\Composer\Configuration\ConfigurationReader',
            $this->_reader->composerReader()
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
        $data->{'output-path'} = 'foo';
        $data->{'source-paths'} = array('bar', 'baz');
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
        $data->{'output-path'} = 'foo';
        $data->{'source-paths'} = array('bar', 'baz');
        $data->{'loader-paths'} = array('qux', 'doom');
        $data->{'validator-namespace'} = 'splat';
        $data->{'use-native-callable'} = false;
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'foo',
            array('bar', 'baz')
        );
        $expected->setLoaderPaths(array('qux', 'doom'));
        $expected->setValidatorNamespace(ClassName::fromString('splat'));
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

    public function testReadTyphoonInferOutputPathSrc()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = new stdClass;
        $data->{'source-paths'} = array('lib', 'src');
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('lib', 'src')
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readTyphoon('doom')
        );
    }

    public function testReadTyphoonInferOutputPathLib()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = new stdClass;
        $data->{'source-paths'} = array('bar', 'lib');
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'lib-typhoon',
            array('bar', 'lib')
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readTyphoon('doom')
        );
    }

    public function testReadTyphoonInferOutputPathDefault()
    {
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        $data = new stdClass;
        $data->{'source-paths'} = array('bar', 'baz');
        Phake::when($this->_reader)
            ->loadJSON(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('bar', 'baz')
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readTyphoon('doom')
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
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "autoload": {
        "psr-0": {
            "Eloquent\\Typhoon\\TypeCheck": "src-typhoon",
            "Eloquent\\Typhoon": "src"
        }
    },
    "extra": {
        "typhoon": {
            "output-path": "foo",
            "source-paths": ["bar", "baz"],
            "validator-namespace": "qux"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'foo',
            array('bar', 'baz')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\qux'));

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
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
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "autoload": {
        "psr-0": {
            "Foo": "src"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Foo\TypeCheck'));

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferValidatorNamespace()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "autoload": {
        "psr-0": {
            "Foo": "src",
            "Bar": "baz"
        }
    },
    "extra": {
        "typhoon": {
            "output-path": "baz"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'baz',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Bar'));

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferValidatorNamespaceFromOutputPath()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "autoload": {
        "psr-0": {
            "Foo": "src",
            "Bar": "baz"
        }
    },
    "extra": {
        "typhoon": {
            "output-path": "baz"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'baz',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Bar'));

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferValidatorNamespaceFromSourcePaths()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "extra": {
        "typhoon": {
            "source-paths": ["foo"]
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('foo')
        );

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerParseAutoload()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "autoload": {
        "psr-0": {
            "Foo\\TypeCheck": "excludeMe",
            "Foo": "foo",
            "Bar": ["bar", "baz", "excludeMe/foo"],
            "Baz": "excludeMe"
        },
        "classmap": ["qux", "excludeMe/bar", "doom"],
        "files": ["excludeMe/baz", "splat", "ping"]
    },
    "include-path": ["pong", "pang", "excludeMe"],
    "extra": {
        "typhoon": {
            "output-path": "excludeMe"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
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
        $expected->setValidatorNamespace(ClassName::fromString('\Foo\TypeCheck'));

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferNativeCallable5_4()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "require": {
        "php": ">=5.4"
    },
    "autoload": {
        "psr-0": {
            "Foo\\TypeCheck": "src-typhoon",
            "Foo": "src"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Foo\TypeCheck'));
        $expected->setUseNativeCallable(true);

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferNativeCallable5_4_x()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "require": {
        "php": ">=5.4.11"
    },
    "autoload": {
        "psr-0": {
            "Foo\\TypeCheck": "src-typhoon",
            "Foo": "src"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Foo\TypeCheck'));
        $expected->setUseNativeCallable(true);

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferNativeCallableMixedConstraint()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "require": {
        "php": "< 5.5.3, >= 5.4"
    },
    "autoload": {
        "psr-0": {
            "Foo\\TypeCheck": "src-typhoon",
            "Foo": "src"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Foo\TypeCheck'));
        $expected->setUseNativeCallable(true);

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
    }

    public function testReadComposerInferNativeCallable64Bit()
    {
        $data = <<<'EOD'
{
    "name": "eloquent/typhoon",
    "require": {
        "php-64bit": ">=5.4"
    },
    "autoload": {
        "psr-0": {
            "Foo\\TypeCheck": "src-typhoon",
            "Foo": "src"
        }
    }
}

EOD;
        Phake::when($this->_isolator)
            ->is_file(Phake::anyParameters())
            ->thenReturn(true)
        ;
        Phake::when($this->_isolator)
            ->file_get_contents(Phake::anyParameters())
            ->thenReturn($data)
        ;
        $expected = new Configuration(
            'src-typhoon',
            array('src')
        );
        $expected->setValidatorNamespace(ClassName::fromString('\Foo\TypeCheck'));
        $expected->setUseNativeCallable(true);

        $this->assertEquals(
            $expected,
            Liberator::liberate($this->_reader)->readComposer('doom')
        );
        Phake::verify($this->_isolator)->is_file('doom/composer.json');
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
            "Invalid JSON in 'bar'. Syntax error."
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
        $jsonData->{'output-path'} = array('foo');
        $jsonData->{'source-paths'} = array('bar', 'baz');
        $jsonData->{'loader-paths'} = array('qux', 'doom');
        $jsonData->{'validator-namespace'} = 'splat';
        $jsonData->{'use-native-callable'} = false;
        $data['Wrong type for output path'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Output path must be a string.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'loader-paths'} = array('qux', 'doom');
        $jsonData->{'validator-namespace'} = 'splat';
        $jsonData->{'use-native-callable'} = false;
        $data['Missing source paths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. At least one source path is required.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'source-paths'} = 'bar';
        $jsonData->{'loader-paths'} = array('qux', 'doom');
        $jsonData->{'validator-namespace'} = 'splat';
        $jsonData->{'use-native-callable'} = false;
        $data['Wrong type for source paths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Source paths must be an array.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'source-paths'} = array('bar', 111);
        $jsonData->{'loader-paths'} = array('qux', 'doom');
        $jsonData->{'validator-namespace'} = 'splat';
        $jsonData->{'use-native-callable'} = false;
        $data['Wrong type for source paths entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Entries in source paths must be strings.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'source-paths'} = array('bar', 'baz');
        $jsonData->{'loader-paths'} = 'qux';
        $jsonData->{'validator-namespace'} = 'doom';
        $jsonData->{'use-native-callable'} = false;
        $data['Wrong type for loader paths'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Loader paths must be an array.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'source-paths'} = array('bar', 'baz');
        $jsonData->{'loader-paths'} = array('qux', 111);
        $jsonData->{'validator-namespace'} = 'doom';
        $jsonData->{'use-native-callable'} = false;
        $data['Wrong type for loader paths entry'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Entries in loader paths must be strings.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'source-paths'} = array('bar', 'baz');
        $jsonData->{'loader-paths'} = array('qux', 'doom');
        $jsonData->{'validator-namespace'} =true;
        $jsonData->{'use-native-callable'} = 'ping';
        $data['Wrong type for validator namespace'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Validator namespace must be a string.",
            $jsonData,
        );

        $jsonData = new stdClass;
        $jsonData->{'output-path'} = 'foo';
        $jsonData->{'source-paths'} = array('bar', 'baz');
        $jsonData->{'loader-paths'} = array('qux', 'doom');
        $jsonData->{'validator-namespace'} = 'splat';
        $jsonData->{'use-native-callable'} = 'ping';
        $data['Wrong type for use native callable option'] = array(
            __NAMESPACE__.'\Exception\InvalidConfigurationException',
            "Invalid configuration. Use native callable option must be a boolean.",
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
