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

use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;
use stdClass;

class ConfigurationValidatorTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_schema = new stdClass;
        $this->_schema->name = 'Typhoon configuration';
        $this->_schema->type = 'object';
        $this->_schema->additionalProperties = false;
        $this->_schema->properties = new stdClass;
        $outputPath = new stdClass;
        $outputPath->type = 'string';
        $outputPath->title = 'The output path for the classes generated by Typhoon.';
        $outputPath->required = true;
        $this->_schema->properties->outputPath = $outputPath;
        $sourcePaths = new stdClass;
        $sourcePaths->type = 'array';
        $sourcePaths->required = true;
        $sourcePaths->minItems = 1;
        $sourcePaths->uniqueItems = true;
        $sourcePaths->items = new stdClass;
        $sourcePaths->items->type = 'string';
        $sourcePaths->items->title = 'A path containing classes to generate validators for.';
        $this->_schema->properties->sourcePaths = $sourcePaths;
        $loaderPaths = new stdClass;
        $loaderPaths->type = 'array';
        $loaderPaths->minItems = 1;
        $loaderPaths->uniqueItems = true;
        $loaderPaths->items = new stdClass;
        $loaderPaths->items->type = 'string';
        $loaderPaths->items->title = 'A path that can be passed to include() to set up autoloading for the classes in the source path(s).';
        $this->_schema->properties->loaderPaths = $loaderPaths;
        $useNativeCallable = new stdClass;
        $useNativeCallable->type = 'boolean';
        $useNativeCallable->title = 'Whether to enforce use of the callable type hint.';
        $this->_schema->properties->useNativeCallable = $useNativeCallable;
        $this->_schemaValidator = Phake::partialMock('JsonSchema\Validator');
        $this->_validator = new ConfigurationValidator(
            $this->_schema,
            $this->_schemaValidator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_schema, $this->_validator->schema());
        $this->assertSame($this->_schemaValidator, $this->_validator->schemaValidator());
    }

    public function testConstructorDefaults()
    {
        $validator = new ConfigurationValidator;

        $this->assertEquals($this->_schema, $validator->schema());
        $this->assertInstanceOf('JsonSchema\Validator', $validator->schemaValidator());
    }

    public function validateData()
    {
        $data = array();

        $configuration = new stdClass;
        $configuration->outputPath = 'foo';
        $configuration->sourcePaths = array('bar');
        $data['Minimal valid config'] = array($configuration);

        $configuration = new stdClass;
        $configuration->outputPath = 'foo';
        $configuration->sourcePaths = array('bar', 'baz');
        $configuration->loaderPaths = array('qux', 'doom');
        $configuration->useNativeCallable = false;
        $data['Full valid config'] = array($configuration);

        return $data;
    }

    /**
     * @dataProvider validateData
     */
    public function testValidate(stdClass $configuration)
    {
        $this->_validator->validate($configuration);

        Phake::inOrder(
            Phake::verify($this->_schemaValidator)->reset(),
            Phake::verify($this->_schemaValidator)->check(
                $this->identicalTo($configuration),
                $this->identicalTo($this->_schema)
            ),
            Phake::verify($this->_schemaValidator)->isValid()
        );
    }

    public function validateFailureData()
    {
        $data = array();

        $configuration = new stdClass;
        $configuration->sourcePaths = array('bar');
        $expected = __NAMESPACE__.'\Exception\InvalidConfigurationException';
        $expectedMessage = "Invalid configuration. 'outputPath' is missing and it is required.";
        $data['Missing outputPath'] = array($expected, $expectedMessage, $configuration);

        $configuration = new stdClass;
        $configuration->outputPath = 'foo';
        $expected = __NAMESPACE__.'\Exception\InvalidConfigurationException';
        $expectedMessage = "Invalid configuration. 'sourcePaths' is missing and it is required.";
        $data['Missing sourcePaths'] = array($expected, $expectedMessage, $configuration);

        $configuration = new stdClass;
        $configuration->outputPath = 'foo';
        $configuration->sourcePaths = array('bar', true);
        $expected = __NAMESPACE__.'\Exception\InvalidConfigurationException';
        $expectedMessage = "Invalid configuration. 'sourcePaths[1]' boolean value found, but a string is required.";
        $data['Invalid type'] = array($expected, $expectedMessage, $configuration);

        return $data;
    }

    /**
     * @dataProvider validateFailureData
     */
    public function testValidateFailure($expected, $expectedMessage, stdClass $configuration)
    {
        $this->setExpectedException($expected, $expectedMessage);
        $this->_validator->validate($configuration);
    }
}
