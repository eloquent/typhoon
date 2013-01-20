<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis;

use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;
use Phake;

class ProjectAnalyzerTest extends MultiGenerationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_classMapper = new ClassMapper;
        $this->_analyzer = new ProjectAnalyzer(
            $this->_classMapper
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->_classMapper, $this->_analyzer->classMapper());
    }

    public function testConstructorDefaults()
    {
        $this->_analyzer = new ProjectAnalyzer;

        $this->assertInstanceOf(
            'Eloquent\Typhoon\ClassMapper\ClassMapper',
            $this->_analyzer->classMapper()
        );
    }

    public function testAnalyzeFailures()
    {
        $configuration = new Configuration(
            'foo',
            array(
                __DIR__.'/../../../../src/Eloquent/Typhoon/TestFixture/AnalyzerFixtures/Failing',
            )
        );
        $actual = $this->_analyzer->analyze($configuration);
        $actualClassesMissingConstructorCall = array();
        foreach ($actual->classesMissingConstructorCall() as $classDefinition) {
            $actualClassesMissingConstructorCall[] = $classDefinition->className()->string();
        }
        $actualClassesMissingProperty = array();
        foreach ($actual->classesMissingProperty() as $classDefinition) {
            $actualClassesMissingProperty[] = $classDefinition->className()->string();
        }

        $this->assertFalse($actual->isSuccessful());
        $this->assertSame(array(
            '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\NoConstructorCall',
        ), $actualClassesMissingConstructorCall);
        $this->assertSame(array(
            '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingProperty',
            '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\NonPrivateProperty',
            '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\StaticProperty',
        ), $actualClassesMissingProperty);
    }

    public function testAnalyzeSuccess()
    {
        $configuration = new Configuration(
            'foo',
            array(
                __DIR__.'/../../../../src/Eloquent/Typhoon/TestFixture/AnalyzerFixtures/Successful',
            )
        );
        $actual = $this->_analyzer->analyze($configuration);

        $this->assertSame(0, count($actual->classesMissingConstructorCall()));
        $this->assertSame(0, count($actual->classesMissingProperty()));
        $this->assertSame(0, count($actual->methodsMissingCall()));
        $this->assertTrue($actual->isSuccessful());
    }
}
