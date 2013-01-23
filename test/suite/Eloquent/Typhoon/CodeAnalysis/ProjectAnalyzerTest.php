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
        $expected = array(
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingCalls',
                'bar',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingCalls',
                'baz',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingCalls',
                'foo',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingConstructorCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingConstructorCall',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingProperty',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingProperty',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingConstructorCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\NoConstructor',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingProperty',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\NonPrivateProperty',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MissingProperty',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\StaticProperty',
            ),
        );

        $this->assertTrue($actual->isError());
        $this->assertAnalysisResult($expected, $actual);
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

        $this->assertFalse($actual->isError());
    }

    protected function assertAnalysisResult(array $expected, AnalysisResult $actual)
    {
        $issues = $actual->issues();
        $sort = array();
        foreach ($issues as $issue) {
            if ($issue instanceof Issue\ClassRelatedIssue) {
                $sort[] = $issue->classDefinition()->className()->string();
            } else {
                $sort[] = '';
            }
        }
        array_multisort($sort, SORT_STRING, $issues);

        $actualArray = '';
        foreach ($issues as $issue) {
            $actualArrayEntry = array(
                get_class($issue)
            );
            if ($issue instanceof Issue\ClassRelatedIssue) {
                $actualArrayEntry[] = $issue->classDefinition()->className()->string();
            }
            if ($issue instanceof Issue\MethodIssue) {
                $actualArrayEntry[] = $issue->methodDefinition()->name();
            }

            $actualArray[] = $actualArrayEntry;
        }

        $this->assertSame($expected, $actualArray);
    }
}
