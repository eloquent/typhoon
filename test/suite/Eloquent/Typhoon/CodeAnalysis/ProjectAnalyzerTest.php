<?php

/*
 * This file is part of the Typhoon package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Typhoon\CodeAnalysis;

use Eloquent\Typhoon\ClassMapper\ClassMapper;
use Eloquent\Typhoon\Configuration\Configuration;
use Eloquent\Typhoon\TestCase\MultiGenerationTestCase;

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
                'Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\InadmissibleMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\CallInDestructor',
                '__destruct',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\InadmissibleMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\CallInToString',
                '__toString',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingConstructorCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\Issue106',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\MissingMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingCalls',
                'bar',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\MissingMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingCalls',
                'baz',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\MethodIssue\MissingMethodCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingCalls',
                'foo',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingConstructorCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingConstructorCall',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingProperty',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\MissingProperty',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingConstructorCall',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\NoConstructor',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingProperty',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\NonPrivateProperty',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DefinedParameterVariableLength',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'definedParameterVariableLength',
                'one',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterByReferenceMismatch',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterByReferenceMismatch',
                'one',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterByReferenceMismatch',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterByReferenceMismatch',
                'two',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterNameMismatch',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterNameMismatch',
                'one',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterNameMismatch',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterNameMismatch',
                'two',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterTypeMismatch',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterTypeMismatch',
                'one',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterTypeMismatch',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterTypeMismatch',
                'two',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterUndefined',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterUndefined',
                'one',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\DocumentedParameterUndefined',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'documentedParameterUndefined',
                'two',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\UndocumentedParameter',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'undocumentedParameter',
                'one',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ParameterIssue\UndocumentedParameter',
                '\Eloquent\Typhoon\TestFixture\AnalyzerFixtures\Failing\ParameterListIssues',
                'undocumentedParameter',
                'two',
            ),
            array(
                'Eloquent\Typhoon\CodeAnalysis\Issue\ClassIssue\MissingProperty',
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

        $this->assertAnalysisResult(array(), $actual);
        $this->assertFalse($actual->isError());
    }

    protected function assertAnalysisResult(array $expected, AnalysisResult $actual)
    {
        $issues = $actual->issues();
        $sort = array();
        foreach ($issues as $issue) {
            $sortString = '';
            if ($issue instanceof Issue\ClassRelatedIssueInterface) {
                $sortString .= $issue->classDefinition()->className()->string();
            }
            if ($issue instanceof Issue\MethodRelatedIssueInterface) {
                $sortString .= '.'.$issue->methodDefinition()->name();
            }
            if ($issue instanceof Issue\ParameterRelatedIssueInterface) {
                $sortString .= '.'.$issue->parameterName();
            }

            $sort[] = $sortString;
        }
        array_multisort($sort, SORT_STRING, $issues);

        $actualArray = array();
        foreach ($issues as $issue) {
            $actualArrayEntry = array(
                get_class($issue)
            );
            if ($issue instanceof Issue\ClassRelatedIssueInterface) {
                $actualArrayEntry[] = $issue->classDefinition()->className()->string();
            }
            if ($issue instanceof Issue\MethodRelatedIssueInterface) {
                $actualArrayEntry[] = $issue->methodDefinition()->name();
            }
            if ($issue instanceof Issue\ParameterRelatedIssueInterface) {
                $actualArrayEntry[] = $issue->parameterName();
            }

            $actualArray[] = $actualArrayEntry;
        }

        $this->assertEquals($expected, $actualArray);
        $this->assertSame($expected, $actualArray);
    }
}
